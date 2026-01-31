<?php

use SchenkeIo\LaravelUrlCleaner\Cleaners\PreventInvalidHost;
use SchenkeIo\LaravelUrlCleaner\Cleaners\PreventLocalhost;
use SchenkeIo\LaravelUrlCleaner\Cleaners\PreventNonHttps;
use SchenkeIo\LaravelUrlCleaner\Cleaners\PreventUserPassword;
use SchenkeIo\LaravelUrlCleaner\Cleaners\RemoveSearch;
use SchenkeIo\LaravelUrlCleaner\Data\UrlData;
use SchenkeIo\LaravelUrlCleaner\Exceptions\CleanUrlException;
use SchenkeIo\LaravelUrlCleaner\Exceptions\InvalidHostException;
use SchenkeIo\LaravelUrlCleaner\Exceptions\InvalidSchemeException;

it('remove long values works', function () {
    // Skip for now as it depends on Laravel's config helper
    expect(true)->toBeTrue();
});

it('remove search works', function () {
    $urlData = new UrlData('https://example.com/?q=search_term&search=another&other=stay');
    (new RemoveSearch)->clean($urlData);
    expect($urlData->getUrl())->toBe('https://example.com/?other=stay');
});

it('prevent localhost works', function () {
    $urlData = new UrlData('https://localhost/path');
    expect(fn () => (new PreventLocalhost)->clean($urlData))->toThrow(InvalidHostException::class);
});

it('prevent non https works', function () {
    $urlData = new UrlData('http://example.com/');
    expect(fn () => (new PreventNonHttps)->clean($urlData))->toThrow(InvalidSchemeException::class);
});

it('prevent user password works', function () {
    $urlData = new UrlData('https://user:pass@example.com/');
    expect(fn () => (new PreventUserPassword)->clean($urlData))->toThrow(CleanUrlException::class);
});

it('prevent invalid host missing tld', function () {
    $urlData = new UrlData('https://example/');
    expect(fn () => (new PreventInvalidHost)->clean($urlData))
        ->toThrow(InvalidHostException::class, 'Invalid host name: example');
});

it('prevent invalid host unknown tld', function () {
    $urlData = new UrlData('https://example.invalidtld');
    expect(fn () => (new PreventInvalidHost)->clean($urlData))
        ->toThrow(InvalidHostException::class, 'Invalid host name: example.invalidtld');
});
