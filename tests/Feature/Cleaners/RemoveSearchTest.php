<?php

use SchenkeIo\LaravelUrlCleaner\Cleaners\RemoveSearch;
use SchenkeIo\LaravelUrlCleaner\Data\UrlData;

it('removes search parameter', function ($urlIn, $urlOut) {
    $removeSearch = new RemoveSearch;
    $urlData = new UrlData($urlIn);
    $removeSearch->clean($urlData);
    expect($urlData->getUrl())->toBe($urlOut);
})->with([
    'set 1' => ['https://test/?q=hallo', 'https://test/'],
    'set 2' => ['https://test/?q=hallo&b=2', 'https://test/?b=2'],
    'set 3' => ['https://test/?q=hallo&b=2&search=x', 'https://test/?b=2'],
]);
