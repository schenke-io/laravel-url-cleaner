<?php

use SchenkeIo\LaravelUrlCleaner\Data\FileIo;
use SchenkeIo\LaravelUrlCleaner\Makers\TopLevelDomains;

it('returns true for invalid tld', function () {
    $fileIo = mock(FileIo::class);
    $fileIo->shouldReceive('getJson')->andReturn(['com', 'org', 'net']);

    $tldManager = new TopLevelDomains($fileIo);
    expect($tldManager->isInvalidTld('invalid'))->toBeTrue();
});

it('returns false for valid tld', function () {
    $fileIo = mock(FileIo::class);
    $fileIo->shouldReceive('getJson')->andReturn(['com', 'org', 'net']);

    $tldManager = new TopLevelDomains($fileIo);
    expect($tldManager->isInvalidTld('com'))->toBeFalse();
});

it('can be initialized via init()', function () {
    $fileIo = mock(FileIo::class);
    $tldManager = TopLevelDomains::init($fileIo);
    expect($tldManager)->toBeInstanceOf(TopLevelDomains::class);
});
