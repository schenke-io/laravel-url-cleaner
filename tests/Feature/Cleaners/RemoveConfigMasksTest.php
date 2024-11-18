<?php

use SchenkeIo\LaravelUrlCleaner\Cleaners\RemoveConfigMasks;
use SchenkeIo\LaravelUrlCleaner\Data\UrlData;

it('can remove keys defined in config', function () {
    config(['url-cleaner.masks' => ['a']]);
    $url = 'http://test.com/?a=12';
    $urlData = new UrlData($url);
    (new RemoveConfigMasks)->clean($urlData);
    expect($urlData->getUrl())->toBe('http://test.com/');
});
