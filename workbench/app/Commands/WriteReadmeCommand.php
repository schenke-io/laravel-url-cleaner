<?php

namespace Workbench\App\Commands;

use Cachet\Badger\Badge;
use Cachet\Badger\Badger;
use Illuminate\Console\Command;
use PhpUnitCoverageBadge\BadgeGenerator;
use PUGX\Poser\Poser;
use PUGX\Poser\Render\SvgPlasticRender;
use ReflectionClass;
use SchenkeIo\LaravelUrlCleaner\Bases\BaseCleaner;
use SchenkeIo\LaravelUrlCleaner\Bases\Source;

class WriteReadmeCommand extends Command
{
    protected $signature = 'make:readme';

    protected $description = 'write the readme file';

    protected string $content = '';

    /**
     * @throws \ReflectionException
     */
    private function getCleanersData(): array
    {
        $cleanerFiles = glob(__DIR__.'/../../../src/Cleaners/*.php');
        $cleaners = [];
        foreach ($cleanerFiles as $file) {
            $classBase = basename($file, '.php');
            $description[$classBase] = '';
            $fullClassname = "SchenkeIo\\LaravelUrlCleaner\\Cleaners\\$classBase";
            $cleaners[$fullClassname]['base'] = $classBase;
            $source = Source::tryFromName($classBase);
            $cleaners[$fullClassname]['masks'] = $source?->maskCount() ?? '-';
            $reflection = new ReflectionClass($fullClassname);
            $cleaners[$fullClassname]['final'] = $reflection->isFinal();

            $comment = $reflection->getDocComment();
            foreach (explode("\n", $comment) as $line) {
                if (preg_match('@^.*?\* (\@\w+|)(.*?)$@', trim($line), $matches)) {
                    [$all, $tag, $content] = $matches;
                    if ($tag) {
                        if (in_array($tag, ['@short', '@task'])) {
                            $cleaners[$fullClassname][$tag] = trim($content);
                        } else {
                            $cleaners[$fullClassname][$tag][] = trim($content);
                        }

                    } else {
                        @$cleaners[$fullClassname]['details'] .= $content."\n";
                    }
                }
            }
        }

        return $cleaners;
    }

    /**
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function handle(): void
    {
        $this->addFile('intro.md');
        $this->addFile('config.md');
        $cleaners = $this->getCleanersData();
        //        print_r($cleaners);
        /*
         * write the maks counts of all files
         */
        $this->content .= <<<'MD'
##  List of cleaner classes

<table>
<tr style="background-color: silver;">
<th>class name</th>
<th># masks</th>
<th>description</th>
</tr>

MD;

        /** @var BaseCleaner $class */
        foreach ($cleaners as $class => $data) {
            $short = $data['base'];
            $this->content .= sprintf(
                "<tr><td>%s</td><td>%s</td><td>%s</td></tr>\n",
                $short,
                $data['masks'],
                $data['@short']
            );
        }
        $this->content .= "</table>\n\n";

        $this->addFile('config.md');

        $this->addFile('masks.md');

        /*
         * extending base classes
         */
        $this->addFile('extending.md');
        foreach ($cleaners as $class => $data) {
            if (! $data['final']) {
                $this->addFile('extending/'.$data['base'].'.md');
            }
        }

        /*
         *  Data sources
         */
        $this->content .= <<<'EOM'

## Data sources

Currently, the following sources are used:

EOM;

        foreach ($cleaners as $class => $data) {
            if (! isset($data['@source'])) {
                continue;
            }
            foreach ($data['@source'] as $source) {
                $this->content .= "- $source\n";
            }
        }

        file_put_contents(__DIR__.'/../../../README.md', $this->content);
        $this->info('README.md file written');

        /*
         * write badge
         */
        $coveredElements = 0;
        $elements = 1;
        foreach (file(__DIR__.'/../../../build/logs/clover.xml') as $line) {
            //     <metrics files elements="565" coveredelements="355"/>
            if (preg_match('@<metrics files.*?elements="(\d+)" coveredelements="(\d+)"/>@', $line, $matches)) {
                [$all, $elements, $coveredElements] = $matches;
                break;
            }
        }
        $coverage = round($elements > 0 ? 100 * $coveredElements / $elements : 0, 0);

        $render = new SvgPlasticRender();
        $poser = new Poser([$render]);

        $svg = $poser->generate('Coverage', $coverage."%", '32CD32', 'plastic');


        file_put_contents(__DIR__.'/../../../.github/coverage-badge.svg',$svg);
        $this->info('coverage-badge.svg file written');
    }

    private function addFile(string $fileName): void
    {
        $this->content .= file_get_contents(__DIR__.'/../../resources/md/'.$fileName);
    }
}
