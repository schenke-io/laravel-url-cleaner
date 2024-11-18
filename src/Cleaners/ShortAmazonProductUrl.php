<?php

namespace SchenkeIo\LaravelUrlCleaner\Cleaners;

use SchenkeIo\LaravelUrlCleaner\Bases\BaseCleaner;
use SchenkeIo\LaravelUrlCleaner\Data\UrlData;

/**
 * @short Amazon product url cleaner
 *
 * @task url_shorting
 * When the url seems to be an Amazon product url the path is changed
 * to the short path variant, all the parameters and the hash are removed
 */
class ShortAmazonProductUrl extends BaseCleaner
{
    /**
     * cleans the UrlData object
     */
    public function clean(UrlData &$urlData): void
    {
        if (preg_match('@amazon\.@', $urlData->host)) {
            // its an amazon url
            if (preg_match('@/(dp|gp/product.*?)/([A-Z0-9]{10})@', $urlData->path, $matches)) {
                // its an amazon product url
                $asin = $matches[2];
                // https://www.amazon.de/dp/B0CP34D8SW
                $urlData->path = "/dp/$asin";
                $urlData->fragment = '';
                $urlData->query = '';
                $urlData->parameter = [];
            }
        }
    }
}
