<?php

use SchenkeIo\LaravelUrlCleaner\Cleaners\PreventLocalhost;
use SchenkeIo\LaravelUrlCleaner\Data\UrlData;
use SchenkeIo\LaravelUrlCleaner\Exceptions\InvalidHostException;

it('can verify localhost', function ($urlIn, $hasException) {
    if ($hasException) {
        $this->expectException(InvalidHostException::class);
    }
    $urlData = new UrlData($urlIn);
    (new PreventLocalhost)->clean($urlData);
    expect($urlData->getUrl())->toBe($urlIn);
})->with([
    'd 1' => ['https://www.test.com', false],
    'd 2' => ['http://localhost/', true],
]);
