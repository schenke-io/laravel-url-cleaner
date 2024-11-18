<?php

namespace SchenkeIo\LaravelUrlCleaner\Cleaners;

use SchenkeIo\LaravelUrlCleaner\Bases\BaseCleaner;
use SchenkeIo\LaravelUrlCleaner\Data\UrlData;

/**
 * @short remove keys defined in the config
 *
 * @task parameter_removal_by_mask
 *
 * @config masks
 */
final class RemoveConfigMasks extends BaseCleaner
{
    /**
     * cleans the UrlData object
     */
    public function clean(UrlData &$urlData): void
    {
        foreach (config('url-cleaner.masks', []) as $mask) {
            $urlData->removeMask($mask);
        }
    }
}
