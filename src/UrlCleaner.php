<?php

namespace SchenkeIo\LaravelUrlCleaner;

use SchenkeIo\LaravelUrlCleaner\Bases\BaseCleaner;
use SchenkeIo\LaravelUrlCleaner\Data\UrlData;
use SchenkeIo\LaravelUrlCleaner\Exceptions\DefectMaskException;
use SchenkeIo\LaravelUrlCleaner\Exceptions\InvalidUrlException;

class UrlCleaner
{
    /**
     * @throws InvalidUrlException
     * @throws DefectMaskException
     */
    public function handle(string $url): string
    {
        /**
         * build an object from the url
         * invalid url's throw an exception
         */
        $urlData = new UrlData($url);
        /** @var BaseCleaner $cleaner */
        foreach (config('url-cleaner.cleaners') as $cleaner) {
            /**
             * each cleaner modify either the url or throw an exception
             */
            (new $cleaner)->clean($urlData);
        }

        /**
         * the modified url gets returned
         */
        return $urlData->getUrl();
    }
}
