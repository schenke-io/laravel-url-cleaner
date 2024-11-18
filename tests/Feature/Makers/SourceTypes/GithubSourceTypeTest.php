<?php

use SchenkeIo\LaravelUrlCleaner\Bases\Source;
use SchenkeIo\LaravelUrlCleaner\Data\FileIo;
use SchenkeIo\LaravelUrlCleaner\Data\WebIo;
use SchenkeIo\LaravelUrlCleaner\Makers\SourceTypes\GithubSourceType;

it('it can get file from github', function () {
    $fileIo = Mockery::mock(FileIo::class);
    $webIo = Mockery::mock(WebIo::class);
    $webIo->shouldReceive('get')->once()->andReturn("a\nb");
    $fileIo->shouldReceive('put')->once()->with(Source::Marketing00->sourceFile(), "a\nb");
    $source = new GithubSourceType(Source::Marketing00, $webIo, $fileIo);
    expect($source->makeCopy())->toBeString()
        ->and($source->getSource())->toBeInstanceOf(Source::class)
        ->and($source->getMasks())->toBeArray();
});
