<?php

use SchenkeIo\LaravelUrlCleaner\Cleaners\PreventNonHttps;
use SchenkeIo\LaravelUrlCleaner\Data\UrlData;
use SchenkeIo\LaravelUrlCleaner\Exceptions\InvalidSchemeException;

it('prevents non https protocols', function ($urlIn, $hasException) {
    if ($hasException) {
        $this->expectException(InvalidSchemeException::class);
    }
    $urlData = new UrlData($urlIn);
    (new PreventNonHttps)->clean($urlData);
    expect($urlData->getUrl())->toBe($urlIn);
})->with([
    'test 1' => ['https://www.test.com', false],
    'test 2' => ['http://localhost/', true],
    'test 3' => ['ftp://localhost/', true],
    'test 4' => ['file://c/d/e.txt', true],
]);
