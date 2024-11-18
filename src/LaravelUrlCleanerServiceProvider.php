<?php

namespace SchenkeIo\LaravelUrlCleaner;

use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Exceptions\InvalidPackage;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelUrlCleanerServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('laravel-url-cleaner')
            ->hasConfigFile('url-cleaner')
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->askToStarRepoOnGitHub('schenke-io/laravel-url-cleaner');
            })
            ->hasTranslations();
    }

    /**
     * Register any application services.
     *
     *
     * @throws InvalidPackage
     */
    public function register(): void
    {
        parent::register();
    }
}
