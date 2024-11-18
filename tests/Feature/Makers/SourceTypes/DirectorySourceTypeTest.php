<?php

use SchenkeIo\LaravelUrlCleaner\Bases\Source;
use SchenkeIo\LaravelUrlCleaner\Data\FileIo;
use SchenkeIo\LaravelUrlCleaner\Makers\SourceTypes\DirectorySourceType;

it('scans a directory and put the files ina summary file', function () {
    $fileIo = Mockery::mock(FileIo::class);
    $fileIo->shouldReceive('glob')->once()->andReturn(['a', 'b']);
    $fileIo->shouldReceive('get')->times(3)->andReturn("a\nb\na\n");
    $fileIo->shouldReceive('put')->once()->with(Source::TopLevelDomains->pathSourceCopy(), "a\nb");
    $source = new DirectorySourceType(Source::TopLevelDomains, $fileIo);
    expect($source->makeCopy())->toBeString()
        ->and($source->getMasks())->toBeArray();
});
