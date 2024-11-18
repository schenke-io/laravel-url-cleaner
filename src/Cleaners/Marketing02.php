<?php

namespace SchenkeIo\LaravelUrlCleaner\Cleaners;

use SchenkeIo\LaravelUrlCleaner\Bases\BaseCleaner;
use SchenkeIo\LaravelUrlCleaner\Bases\Source;
use SchenkeIo\LaravelUrlCleaner\Data\UrlData;
use SchenkeIo\LaravelUrlCleaner\Exceptions\DefectMaskException;

/**
 * @short url-parameter-tracker-list from https://github.com/spekulatius
 *
 * @task parameter_removal_by_mask
 *
 * @source https://github.com/spekulatius/url-parameter-tracker-list
 *
 * This repository has a list of query parameters, which are used to remove them from given urls.
 */
final class Marketing02 extends BaseCleaner
{
    protected static string $fileBase = 'spekulatius/url-parameter-tracker-list';

    /**
     * cleans the UrlData object
     *
     * @throws DefectMaskException
     */
    public function clean(UrlData &$urlData): void
    {
        $this->removeParameterKeysUsingSource($urlData);
    }
}
