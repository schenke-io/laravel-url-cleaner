<?php

use SchenkeIo\LaravelUrlCleaner\Data\FileIo;
use SchenkeIo\LaravelUrlCleaner\Makers\TopLevelDomains;

it('can check tld', function ($tld, $isInvalid) {
    $fileIo = Mockery::mock(FileIo::class);
    $fileIo->shouldReceive('getJson')->once()->andReturn(['com', 'net']);
    $domains = TopLevelDomains::init($fileIo);
    expect($domains->isInvalidTld($tld))->toBe($isInvalid);
})->with([
    // name tld $isInvalid
    'tld 1' => ['com', false],
    'tld 2' => ['de', true],
]);
