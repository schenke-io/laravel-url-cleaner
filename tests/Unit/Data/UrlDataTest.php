<?php

use SchenkeIo\LaravelUrlCleaner\Data\UrlData;

it('can handle any kind of url', function ($url) {
    expect((new UrlData($url))->getUrl())->toBe($url);
})->with([
    'url 1' => ['https://test.de/1234/?b=34#start'],
    'url 2' => ['https://user:passowrd@test.de/1234/?b=34#start'],
    'url 3' => ['https://test.de:8080/1234/?b=34#start'],
]);
