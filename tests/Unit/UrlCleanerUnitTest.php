<?php

namespace Tests\Unit;

use SchenkeIo\LaravelUrlCleaner\Cleaners\RemoveSearch;
use SchenkeIo\LaravelUrlCleaner\UrlCleaner;

test('UrlCleaner handle method works correctly', function ($urlIn, $urlOut) {
    /*
     * We use a dummy class to avoid calling config() if possible,
     * but UrlCleaner calls config('url-cleaner.cleaners').
     * So we must provide it.
     */
    $urlCleaner = new class extends UrlCleaner
    {
        public function handle(string $url): string
        {
            $urlData = new \SchenkeIo\LaravelUrlCleaner\Data\UrlData($url);
            (new RemoveSearch)->clean($urlData);

            return $urlData->getUrl();
        }
    };

    expect($urlCleaner->handle($urlIn))->toBe($urlOut);
})->with([
    'remove search' => ['https://localhost/?q=123', 'https://localhost/'],
]);

test('UrlCleaner handle method handles invalid urls', function () {
    $urlCleaner = new UrlCleaner;
    // We need to trigger InvalidUrlException from UrlData
    // which is called by UrlCleaner::handle
    $urlCleaner->handle('http:/ /invalid');
})->throws(\SchenkeIo\LaravelUrlCleaner\Exceptions\InvalidUrlException::class);
