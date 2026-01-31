<?php

namespace SchenkeIo\LaravelUrlCleaner\Facades;

use Illuminate\Support\Facades\Facade;
use SchenkeIo\LaravelUrlCleaner\UrlCleaner as UrlCleanerService;

/**
 * @method static string handle(string $url)
 *
 * @see \SchenkeIo\LaravelUrlCleaner\UrlCleaner
 */
class UrlCleaner extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return UrlCleanerService::class;
    }
}
