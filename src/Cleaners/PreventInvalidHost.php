<?php

namespace SchenkeIo\LaravelUrlCleaner\Cleaners;

use SchenkeIo\LaravelUrlCleaner\Bases\BaseCleaner;
use SchenkeIo\LaravelUrlCleaner\Bases\Source;
use SchenkeIo\LaravelUrlCleaner\Data\UrlData;
use SchenkeIo\LaravelUrlCleaner\Exceptions\FileIoException;
use SchenkeIo\LaravelUrlCleaner\Exceptions\InvalidHostException;
use SchenkeIo\LaravelUrlCleaner\Makers\TopLevelDomains;

/**
 * @short do not allow urls with invalid host names
 *
 * @task prevention
 *
 * @source https://data.iana.org/TLD/tlds-alpha-by-domain.txt
 */
final class PreventInvalidHost extends BaseCleaner
{
    /**
     * cleans the UrlData object
     *
     * @throws InvalidHostException
     * @throws FileIoException
     */
    public function clean(UrlData &$urlData): void
    {
        /*
         * host must be made out of two parts
         */
        $hostParts = explode('.', $urlData->host);
        if (count($hostParts) < 2) {
            throw new InvalidHostException($urlData->host, 'no tld found');
        }
        /*
         * must have valid tld
         */
        $tld = end($hostParts);

        if (TopLevelDomains::init($this->fileIo)->isInvalidTld($tld)) {
            throw new InvalidHostException($urlData->host, 'unknown tld');
        }
    }
}
