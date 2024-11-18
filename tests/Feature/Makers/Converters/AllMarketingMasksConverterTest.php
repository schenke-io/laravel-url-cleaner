<?php

use SchenkeIo\LaravelUrlCleaner\Bases\Source;
use SchenkeIo\LaravelUrlCleaner\Data\FileIo;
use SchenkeIo\LaravelUrlCleaner\Makers\Converters\AllMarketingMasksConverter;

test('all marketing sources are converted', function ($source) {
    $fileIo = Mockery::mock(FileIo::class);
    $fileIo->shouldReceive('getJson')->andReturn([]);
    $fileIo->shouldReceive('putJson')->andReturn(true);
    $converter = new AllMarketingMasksConverter($source, $fileIo);
    $this->assertIsString($converter->convert($source));
})->with(Source::cases());
