<?php

namespace Workbench\App\Providers;

use Illuminate\Support\ServiceProvider;
use Workbench\App\Commands\MakeDataCommand;
use Workbench\App\Commands\WriteReadmeCommand;

class WorkbenchServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                WriteReadmeCommand::class,
                MakeDataCommand::class,
            ]);
        }
    }
}
