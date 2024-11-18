<?php

use SchenkeIo\LaravelUrlCleaner\Cleaners\PreventInvalidHost;
use SchenkeIo\LaravelUrlCleaner\Data\FileIo;
use SchenkeIo\LaravelUrlCleaner\Data\UrlData;
use SchenkeIo\LaravelUrlCleaner\Exceptions\InvalidHostException;

it('prevents domains without tld', function ($url, $hasException) {
    if ($hasException) {
        $this->expectException(InvalidHostException::class);
    }
    $urlData = new UrlData($url);
    (new PreventInvalidHost)->clean($urlData);
    expect($urlData->getUrl())->toBe($url);
})->with([
    // name      url   / exception?
    'url 1' => ['https://locolhost/', true],
    'url 2' => ['http://test/', true],
]);

it('can verify host names', function ($url, $hasException) {
    if ($hasException) {
        $this->expectException(InvalidHostException::class);
    }
    $urlData = new UrlData($url);
    $fileIo = new FileIo; // we read test data
    $cleaner = new PreventInvalidHost($fileIo);
    $cleaner->clean($urlData);
    expect($urlData->getUrl())->toBe($url);
})->with([
    'd 1' => ['https://www.test.com', false],
    'd 2' => ['https://www.test.coooo', true],
]);
