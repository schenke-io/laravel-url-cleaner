<?php

use SchenkeIo\LaravelUrlCleaner\Bases\Source;
use SchenkeIo\LaravelUrlCleaner\Data\FileIo;
use SchenkeIo\LaravelUrlCleaner\Makers\Converters\TextLineMasksConverter;

it('can read data from single line file', function () {
    $fileIo = Mockery::mock(FileIo::class);
    $fileIo->shouldReceive('get')->once()->andReturn('a,b,c');
    $fileIo->shouldReceive('putJson')->once();

    $converter = new TextLineMasksConverter(',', $fileIo);
    expect($converter->convert(Source::TopLevelDomains))->toBeString();
});

it('can handle empty delimiter', function () {
    $fileIo = Mockery::mock(FileIo::class);
    $fileIo->shouldReceive('get')->once()->andReturn('a,b,c');
    $fileIo->shouldReceive('putJson')->once();

    $converter = new TextLineMasksConverter('', $fileIo);
    expect($converter->convert(Source::TopLevelDomains))->toBeString();
});
