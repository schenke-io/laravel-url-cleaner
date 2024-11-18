<?php

use SchenkeIo\LaravelUrlCleaner\Cleaners\RemoveLongValues;
use SchenkeIo\LaravelUrlCleaner\Data\UrlData;

it('can remove kes of long values', function () {

    config(['url-cleaner.max_length_value' => 7]);

    $url = 'http://test.com/?a=12&b=abcdefghijklmnopqrstuvwxyz';
    $urlData = new UrlData($url);
    (new RemoveLongValues)->clean($urlData);
    expect($urlData->getUrl())->toBe('http://test.com/?a=12');
});
