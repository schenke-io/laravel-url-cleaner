<?php

use SchenkeIo\LaravelUrlCleaner\Bases\Source;
use SchenkeIo\LaravelUrlCleaner\Data\FileIo;
use SchenkeIo\LaravelUrlCleaner\Makers\Converters\ArrayConverter;
use SchenkeIo\LaravelUrlCleaner\Makers\SourceTypes\NoSourceType;
use SchenkeIo\LaravelUrlCleaner\Makers\Transformer;

it('handles a transformation', function () {
    $fileIo = Mockery::mock(FileIo::class);
    $source = Mockery::mock(NoSourceType::class);
    $source->shouldReceive('getSource')->once()->andReturn(Source::Marketing00, $fileIo);
    $source->shouldReceive('makeCopy')->once();
    $converter = Mockery::mock(ArrayConverter::class);
    $converter->shouldReceive('convert')->once();
    $transformer = new Transformer($source, $converter);
    expect($transformer->handle())->toBeString();
});
