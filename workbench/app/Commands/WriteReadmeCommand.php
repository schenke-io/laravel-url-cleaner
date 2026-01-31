<?php

namespace Workbench\App\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use SchenkeIo\LaravelUrlCleaner\Bases\Source;
use SchenkeIo\PackagingTools\Badges\MakeBadge;
use SchenkeIo\PackagingTools\Markdown\ClassReader;
use SchenkeIo\PackagingTools\Markdown\MarkdownAssembler;
use SchenkeIo\PackagingTools\Setup\ProjectContext;

use function Orchestra\Testbench\package_path;

class WriteReadmeCommand extends Command
{
    protected $signature = 'make:readme';

    protected $description = 'write the readme file';

    public function handle(): void
    {
        $projectContext = new ProjectContext(File::getFacadeRoot(), package_path(), 'src');

        /*
         * generate coverage badge
         */
        MakeBadge::makeCoverageBadge('coverage.xml', $projectContext)->store('.github/coverage-badge.svg');
        $this->info('Coverage badge generated');

        $assembler = new MarkdownAssembler('workbench/resources/md', $projectContext);

        $assembler->addTableOfContents();
        $assembler->badges()->all();
        $assembler->addMarkdown('header.md');

        $assembler->addMarkdown('config.md');

        /*
         * Cleaners Table
         */
        $cleanerFiles = File::glob(package_path('src/Cleaners/*.php'));
        $tableData = [['class name', '# masks', 'description']];
        foreach ($cleanerFiles as $file) {
            $classBase = File::name($file);
            /** @var class-string $fullClassname */
            $fullClassname = "SchenkeIo\\LaravelUrlCleaner\\Cleaners\\$classBase";
            $source = Source::tryFromName($classBase);
            $maskCount = $source?->maskCount() ?? '-';

            $classReader = ClassReader::fromClass($fullClassname, $projectContext);
            $classData = $classReader->getClassDataFromClass($fullClassname);

            $tableData[] = [
                $classBase,
                (string) $maskCount,
                $classData['short'] ?? '',
            ];
        }
        $assembler->tables()->fromArray($tableData);

        $assembler->addMarkdown('masks.md');
        $assembler->addMarkdown('extending.md');

        /*
         * Extending details
         */
        foreach ($cleanerFiles as $file) {
            $classBase = File::name($file);
            /** @var class-string $fullClassname */
            $fullClassname = "SchenkeIo\\LaravelUrlCleaner\\Cleaners\\$classBase";
            $reflection = new \ReflectionClass($fullClassname);
            if (! $reflection->isFinal()) {
                $extendingFile = "extending/$classBase.md";
                if (File::exists(package_path("workbench/resources/md/$extendingFile"))) {
                    $assembler->addMarkdown($extendingFile);
                }
            }
        }

        /*
         * Skills
         */
        $assembler->addText('## AI Skills');
        $assembler->skillOverview()->all();
        $assembler->skills()->all();

        $assembler->writeMarkdown('README.md');

        $this->info('README.md file written');
    }
}
