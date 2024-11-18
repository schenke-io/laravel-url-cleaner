<?php

use SchenkeIo\LaravelUrlCleaner\Bases\Source;
use SchenkeIo\LaravelUrlCleaner\Data\FileIo;
use SchenkeIo\LaravelUrlCleaner\Makers\Converters\TextLinesMasksConverter;

it('converts a file of lines', function () {
    $fileIo = Mockery::mock(FileIo::class);
    $fileIo->shouldReceive('get')->once()->andReturn("a\nb\nc");
    $fileIo->shouldReceive('putJson')->once()->with(Source::TopLevelDomains->pathFinalJson(), ['' => ['a', 'b', 'c']]);

    $converter = new TextLinesMasksConverter('/(.*)/', $fileIo);
    expect($converter->convert(Source::TopLevelDomains))->toBeString();
});
