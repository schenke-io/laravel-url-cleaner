<?php

use Illuminate\Support\Facades\Config;
use SchenkeIo\LaravelUrlCleaner\Data\UrlData;
use SchenkeIo\LaravelUrlCleaner\Exceptions\InvalidUrlException;

it('raise exception on invalid url', function ($url) {
    $this->expectException(InvalidUrlException::class);
    new UrlData($url);
})->with([
    'http:/ /test.com',
]);

it('can remove parameter key', function ($urlIn, $urlOut, $key) {
    $urlData = new UrlData($urlIn);
    $urlData->removeParameterKey($key);
    expect($urlData->getUrl())->toBe($urlOut);
})->with([
    'simple 1' => ['http://test.com/?a=1', 'http://test.com/', 'a'],
    'simple 2' => ['http://test.com/?a=1&b=2', 'http://test.com/?b=2', 'a'],
    'simple 3' => ['http://test.com/?a=1#a', 'http://test.com/#a', 'a'],
]);

it('detects when a cleaner make invalid urls', function () {
    $url = 'http://test.com/abc?a=1&b=2';
    $this->expectException(InvalidUrlException::class);
    $urlData = new UrlData($url);
    $urlData->host = "a\nb";
    $newUrl = $urlData->getUrl();
});

it('do not remove a key when protected', function () {
    $url = 'https://test.com/abc?a=1&b=2';
    Config::set('url-cleaner.protected_keys', ['b']);
    $urlData = new UrlData($url);
    $urlData->removeParameterKey('b');
    expect($urlData->getUrl())->toBe($url);
});
