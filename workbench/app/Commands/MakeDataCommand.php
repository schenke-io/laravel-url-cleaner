<?php

namespace Workbench\App\Commands;

use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use SchenkeIo\LaravelUrlCleaner\Bases\Source;
use SchenkeIo\LaravelUrlCleaner\Exceptions\FileIoException;

class MakeDataCommand extends Command
{
    protected $signature = 'make:data';

    protected $description = 'grab, parse and write data files';

    /**
     * @throws FileIoException
     * @throws GuzzleException
     */
    public function handle(): void
    {
        $this->info(Source::Marketing00->makeSource());
        $this->info(Source::Marketing01->makeSource());
        $this->info(Source::Marketing02->makeSource());
        $this->info(Source::Marketing03->makeSource());
        $this->info(Source::Marketing04->makeSource());
        $this->info(Source::MarketingUnique->makeSource());
        $this->info(Source::MarketingNarrow->makeSource());
        $this->info(Source::MarketingBroad->makeSource());
        $this->info(Source::TopLevelDomains->makeSource());

    }
}
