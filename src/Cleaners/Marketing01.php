<?php

namespace SchenkeIo\LaravelUrlCleaner\Cleaners;

use SchenkeIo\LaravelUrlCleaner\Bases\BaseCleaner;
use SchenkeIo\LaravelUrlCleaner\Bases\Source;
use SchenkeIo\LaravelUrlCleaner\Data\UrlData;
use SchenkeIo\LaravelUrlCleaner\Exceptions\DefectMaskException;

/**
 * @short tracking-query-params-registry from https://github.com/mpchadwick
 *
 * @task parameter_removal_by_mask
 *
 * @source https://github.com/mpchadwick/tracking-query-params-registry
 *
 * This repository has a list of query parameters, which are used to remove them from given urls.
 */
final class Marketing01 extends BaseCleaner
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
