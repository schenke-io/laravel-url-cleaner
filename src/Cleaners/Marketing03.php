<?php

namespace SchenkeIo\LaravelUrlCleaner\Cleaners;

use SchenkeIo\LaravelUrlCleaner\Bases\BaseCleaner;
use SchenkeIo\LaravelUrlCleaner\Bases\Source;
use SchenkeIo\LaravelUrlCleaner\Data\UrlData;
use SchenkeIo\LaravelUrlCleaner\Exceptions\DefectMaskException;

/**
 * @short Neat-URL from https://github.com/Smile4ever
 *
 * @task parameter_removal_by_mask
 *
 * @source https://github.com/Smile4ever/Neat-URL
 *
 * This repository has a list of query parameters, which are used to remove them from given urls.
 */
final class Marketing03 extends BaseCleaner
{
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
