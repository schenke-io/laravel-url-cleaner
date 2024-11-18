<?php

namespace SchenkeIo\LaravelUrlCleaner\Cleaners;

use SchenkeIo\LaravelUrlCleaner\Bases\BaseCleaner;
use SchenkeIo\LaravelUrlCleaner\Data\UrlData;
use SchenkeIo\LaravelUrlCleaner\Exceptions\InvalidSchemeException;

/**
 * @short do not allow urls different from the scheme https
 *
 * @task prevention
 */
class PreventNonHttps extends BaseCleaner
{
    protected array $allowedSchemes = ['https'];

    /**
     * cleans the UrlData object
     *
     * @throws InvalidSchemeException
     */
    public function clean(UrlData &$urlData): void
    {
        if (! in_array($urlData->scheme, $this->allowedSchemes)) {
            throw new InvalidSchemeException($urlData->scheme, $this->allowedSchemes);
        }
    }
}
