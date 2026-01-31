<?php

namespace Tests\Feature;

use SchenkeIo\LaravelUrlCleaner\Facades\UrlCleaner as UrlCleanerFacade;
use SchenkeIo\LaravelUrlCleaner\Tests\TestCase;
use SchenkeIo\LaravelUrlCleaner\UrlCleaner;

class ServiceProviderTest extends TestCase
{
    public function test_url_cleaner_is_registered_as_singleton()
    {
        $instance1 = $this->app->make(UrlCleaner::class);
        $instance2 = $this->app->make(UrlCleaner::class);

        $this->assertInstanceOf(UrlCleaner::class, $instance1);
        $this->assertSame($instance1, $instance2);
    }

    public function test_facade_works()
    {
        $url = 'https://example.com/?q=123';
        // By default it might not have any cleaners if not configured,
        // but calling it through facade should work and use the singleton.
        $result = UrlCleanerFacade::handle($url);
        $this->assertIsString($result);
    }
}
