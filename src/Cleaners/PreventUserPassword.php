<?php

namespace SchenkeIo\LaravelUrlCleaner\Cleaners;

use SchenkeIo\LaravelUrlCleaner\Bases\BaseCleaner;
use SchenkeIo\LaravelUrlCleaner\Data\UrlData;
use SchenkeIo\LaravelUrlCleaner\Exceptions\CleanUrlException;

/**
 * @short do not allow urls using user and passwords
 *
 * @task prevention
 */
final class PreventUserPassword extends BaseCleaner
{
    /**
     * cleans the UrlData object
     *
     * @throws CleanUrlException
     */
    public function clean(UrlData &$urlData): void
    {
        if ($urlData->user !== '' || $urlData->pass !== '') {
            throw new CleanUrlException('The use of user and password in urls is not accepted.');
        }
    }
}
