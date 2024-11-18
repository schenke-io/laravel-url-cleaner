<?php

use SchenkeIo\LaravelUrlCleaner\Bases\Source;
use SchenkeIo\LaravelUrlCleaner\Data\FileIo;
use SchenkeIo\LaravelUrlCleaner\Makers\Converters\JsonMasksConverter;

it('can convert json structures', function () {
    $source = [
        'main' => [
            ['data' => ['a', 'b', 'c']],
            ['data' => ['d']],
            ['data' => ['z@test.com']],
        ],
    ];
    $target = [
        '' => ['a', 'b', 'c', 'd'],
        'test.com' => ['z'],
    ];
    $fileIo = Mockery::mock(FileIo::class);
    $fileIo->shouldReceive('getJson')->once()->andReturn($source);
    $fileIo->shouldReceive('putJson')->once()->with('final/marketing00.json', $target);

    $converter = new JsonMasksConverter('main.*.data', $fileIo);
    $this->assertIsString($converter->convert(Source::Marketing00));
});
