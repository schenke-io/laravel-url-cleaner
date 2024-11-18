<?php

namespace SchenkeIo\LaravelUrlCleaner\Cleaners;

use SchenkeIo\LaravelUrlCleaner\Bases\BaseCleaner;
use SchenkeIo\LaravelUrlCleaner\Data\UrlData;
use SchenkeIo\LaravelUrlCleaner\Exceptions\InvalidHostException;

/**
 * @short do not allow urls from localhost
 *
 * @task prevention
 */
class PreventLocalhost extends BaseCleaner
{
    protected array $hostRegExes = ['/localhost/'];

    /**
     * cleans the UrlData object
     *
     * @throws InvalidHostException
     */
    public function clean(UrlData &$urlData): void
    {
        foreach ($this->hostRegExes as $regex) {
            if (preg_match($regex, $urlData->host)) {
                throw new InvalidHostException($urlData->host, 'host now allowed');
            }
        }
    }
}
