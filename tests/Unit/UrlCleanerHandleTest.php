<?php

namespace SchenkeIo\LaravelUrlCleaner\Tests\Unit;

use Illuminate\Support\Facades\Config;
use SchenkeIo\LaravelUrlCleaner\UrlCleaner;

test('it can handle null or non-array cleaners config', function ($cleanerList) {
    Config::set('url-cleaner.cleaners', $cleanerList);
    $urlCleaner = new UrlCleaner;
    $url = 'https://example.com/?q=123';
    expect($urlCleaner->handle($url))->toBe($url);
})->with([
    'null' => [null],
    'string' => ['not an array'],
    'integer' => [123],
]);
