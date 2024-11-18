<?php

use SchenkeIo\LaravelUrlCleaner\Cleaners\SortParameters;
use SchenkeIo\LaravelUrlCleaner\Data\UrlData;

it('do sort the parameters', function () {
    $urlData = new UrlData('http://test.com/?z=1&a=2');
    (new SortParameters)->clean($urlData);
    expect($urlData->getUrl())->toBe('http://test.com/?a=2&z=1');
});
