<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Config;
use SchenkeIo\LaravelUrlCleaner\Cleaners\RemoveSearch;
use SchenkeIo\LaravelUrlCleaner\Tests\TestCase;
use SchenkeIo\LaravelUrlCleaner\UrlCleaner;

class UrlCleanerFeatureTest extends TestCase
{
    public function test_handle_with_cleaners()
    {
        Config::set('url-cleaner.cleaners', [
            RemoveSearch::class,
        ]);

        $urlCleaner = new UrlCleaner;
        $url = 'https://example.com/?q=123&other=abc';
        $result = $urlCleaner->handle($url);

        $this->assertEquals('https://example.com/?other=abc', $result);
    }
}
