<?php

namespace SchenkeIo\LaravelUrlCleaner\Tests\Unit\Data;

use Illuminate\Support\Facades\Config;
use Mockery;
use SchenkeIo\LaravelUrlCleaner\Data\UrlData;
use SchenkeIo\LaravelUrlCleaner\Exceptions\InvalidUrlException;
use SchenkeIo\LaravelUrlCleaner\Tests\TestCase;

// uses(TestCase::class);

it('can get and set host', function () {
    $urlData = new UrlData('https://example.com/path');
    expect($urlData->getHost())->toBe('example.com');
    $urlData->setHost('another.com');
    expect($urlData->getHost())->toBe('another.com')
        ->and($urlData->getUrl())->toBe('https://another.com/path');
});

it('can get and set scheme', function () {
    $urlData = new UrlData('https://example.com/path');
    expect($urlData->getScheme())->toBe('https');
    $urlData->setScheme('http');
    expect($urlData->getScheme())->toBe('http')
        ->and($urlData->getUrl())->toBe('http://example.com/path');
});

it('can get path', function () {
    $urlData = new UrlData('https://example.com/some/path?a=1');
    expect($urlData->getPath())->toBe('/some/path');
});

it('can get domain', function () {
    $urlData = new UrlData('https://sub.example.com/path');
    expect($urlData->getDomain())->toBe('sub.example.com');
});

it('can get parameter keys', function () {
    $urlData = new UrlData('https://example.com/?a=1&b=2');
    expect($urlData->getParameterKeys())->toBe(['a', 'b']);
});

it('can handle full host', function () {
    $urlData = new UrlData('https://user:pass@sub.example.com:8080/path');
    expect($urlData->fullHost())->toBe('https://user:pass@sub.example.com:8080');
});

it('can remove mask', function () {
    $urlData = new UrlData('https://example.com/?utm_source=google&q=search');
    $urlData->removeMask('utm_*');
    expect($urlData->getUrl())->toBe('https://example.com/?q=search');
});

it('can remove multiple masks', function () {
    $urlData = new UrlData('https://example.com/?utm_source=google&utm_medium=email&q=search');
    $urlData->removeMask('utm_*');
    expect($urlData->getUrl())->toBe('https://example.com/?q=search');
});

it('does not remove when domain does not match', function () {
    $urlData = new UrlData('https://example.com/?q=search');
    $urlData->removeMask('q@other.com');
    expect($urlData->getUrl())->toBe('https://example.com/?q=search');
});

it('removes when domain matches', function () {
    $urlData = new UrlData('https://example.com/?q=search');
    $urlData->removeMask('q@example.com');
    expect($urlData->getUrl())->toBe('https://example.com/');
});

it('covers removeParameterKey with protected keys', function () {
    Config::set('url-cleaner.protected_keys', ['keep_me']);

    $urlData = new UrlData('https://example.com/?keep_me=1&remove_me=2');
    $urlData->removeParameterKey('keep_me');
    $urlData->removeParameterKey('remove_me');

    expect($urlData->getUrl())->toContain('keep_me=1')
        ->and($urlData->getUrl())->not->toContain('remove_me=2');
});

it('covers fullHost with user but no pass', function () {
    $urlData = new UrlData('https://user@example.com');
    expect($urlData->fullHost())->toBe('https://user@example.com');
});

it('throws InvalidUrlException when generated URL is invalid', function () {
    $urlData = new UrlData('https://example.com');
    // Manually corrupting the internal state to produce an invalid URL
    // filter_var('https://:80', FILTER_VALIDATE_URL) is false
    $urlData->host = '';
    $urlData->scheme = 'https';
    $urlData->port = '80';

    // We need to bypass the constructor check if we wanted to test it there,
    // but here we test getUrl() reconstruction.

    expect(fn () => $urlData->getUrl())->toThrow(InvalidUrlException::class);
});

it('covers removeParameterKey when config is missing or throws', function () {
    // Attempting to mock config() via app instance
    $mockConfig = Mockery::mock(\Illuminate\Contracts\Config\Repository::class);

    // Mock all potential calls to get() that Laravel might make during service resolution
    $mockConfig->shouldReceive('get')
        ->with('url-cleaner.protected_keys')
        ->andThrow(new \Exception('Config error'));

    $mockConfig->shouldReceive('get')->andReturn(null);

    app()->instance('config', $mockConfig);

    $urlData = new UrlData('https://example.com/?a=1');
    $urlData->removeParameterKey('a');

    expect($urlData->getUrl())->toBe('https://example.com/');
});
