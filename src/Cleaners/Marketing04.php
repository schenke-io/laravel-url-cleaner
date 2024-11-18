<?php

namespace SchenkeIo\LaravelUrlCleaner\Cleaners;

use SchenkeIo\LaravelUrlCleaner\Bases\BaseCleaner;
use SchenkeIo\LaravelUrlCleaner\Bases\Source;
use SchenkeIo\LaravelUrlCleaner\Data\UrlData;
use SchenkeIo\LaravelUrlCleaner\Exceptions\DefectMaskException;

/**
 * @short platform-url-click-id-parameters from https://github.com/henkisdabro
 *
 * @task parameter_removal_by_mask
 *
 * @source https://github.com/henkisdabro/platform-url-click-id-parameters
 *
 * This repository has a list of query parameters, which are used to remove them from given urls.
 */
final class Marketing04 extends BaseCleaner
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
