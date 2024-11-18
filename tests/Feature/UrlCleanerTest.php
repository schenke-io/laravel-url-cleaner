<?php

use SchenkeIo\LaravelUrlCleaner\Cleaners\RemoveSearch;
use SchenkeIo\LaravelUrlCleaner\UrlCleaner;

it('can clean the url based on the config file', function ($urlIn, $urlOut) {
    Config::set('url-cleaner', [
        'cleaners' => [
            RemoveSearch::class,
        ],
    ]);
    $urlCleaner = new UrlCleaner;
    expect($urlCleaner->handle($urlIn))->toBe($urlOut);

})->with([
    'case 1' => ['https://localhost/?q=123', 'https://localhost/'],
]);
