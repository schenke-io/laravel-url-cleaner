<?php

namespace SchenkeIo\LaravelUrlCleaner\Cleaners;

use SchenkeIo\LaravelUrlCleaner\Bases\BaseCleaner;
use SchenkeIo\LaravelUrlCleaner\Data\UrlData;

/**
 * @short the query parameters get alphabetical sorted
 *
 * @task beautification
 */
final class SortParameters extends BaseCleaner
{
    /**
     * cleans the UrlData object
     */
    public function clean(UrlData &$urlData): void
    {
        ksort($urlData->parameter);
    }
}
