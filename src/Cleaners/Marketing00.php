<?php

namespace SchenkeIo\LaravelUrlCleaner\Cleaners;

use SchenkeIo\LaravelUrlCleaner\Bases\BaseCleaner;
use SchenkeIo\LaravelUrlCleaner\Bases\Source;
use SchenkeIo\LaravelUrlCleaner\Data\UrlData;
use SchenkeIo\LaravelUrlCleaner\Exceptions\DefectMaskException;

/**
 * @short Manual collected list of parameters for cleaning
 *
 * @task parameter_removal_by_mask
 *
 * The data is manually taken from websites and copied in files for aggregation.
 *
 * @source https://docs.flyingpress.com/en/article/ignore-query-parameters-yfejfj/
 * @source https://support.cloudways.com/en/articles/8437462-how-to-enable-ignore-query-string-for-varnish-cache
 */
final class Marketing00 extends BaseCleaner
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
