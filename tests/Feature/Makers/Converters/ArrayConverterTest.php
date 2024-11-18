<?php

use SchenkeIo\LaravelUrlCleaner\Bases\Source;
use SchenkeIo\LaravelUrlCleaner\Data\FileIo;
use SchenkeIo\LaravelUrlCleaner\Makers\Converters\ArrayConverter;

it('can convert arrays', function ($source, $json, $skipComments, $toLower) {
    $fileIo = Mockery::mock(FileIo::class);
    $fileIo->shouldReceive('get')->once()->andReturn($source);
    $fileIo->shouldReceive('putJson')->once()->with(Source::TopLevelDomains->pathFinalJson(), $json);

    $converter = new ArrayConverter('/.*/', $fileIo, $skipComments, $toLower);
    expect($converter->convert(Source::TopLevelDomains))->toBeString();
})->with([
    // name       content json              skipComments   toLower
    'case 1' => ["a\nb\nc", ['a', 'b', 'c'], false, false],
    'case 2' => ["A\nB\nC", ['A', 'B', 'C'], false, false],
    'case 3' => ["A\nB\nC", ['a', 'b', 'c'], false, true],
    'case 4' => ["# something\nA\nB\nC", ['a', 'b', 'c'], true, true],
]);
