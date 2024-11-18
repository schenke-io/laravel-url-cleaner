<?php

namespace SchenkeIo\LaravelUrlCleaner\Cleaners;

use SchenkeIo\LaravelUrlCleaner\Bases\BaseCleaner;
use SchenkeIo\LaravelUrlCleaner\Data\UrlData;

/**
 * @short remove overly long parameters.
 *
 * @task parameter_removal_by_value
 *
 * @config max_length_value
 */
final class RemoveLongValues extends BaseCleaner
{
    /**
     * cleans the UrlData object
     */
    public function clean(UrlData &$urlData): void
    {
        foreach ($urlData->parameter as $key => $value) {
            if (strlen($value) > config('url-cleaner.max_length_value')) {
                $urlData->removeParameterKey($key);
            }
        }
    }
}
