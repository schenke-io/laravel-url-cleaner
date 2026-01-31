<?php

use SchenkeIo\LaravelUrlCleaner\Bases\Source;
use SchenkeIo\LaravelUrlCleaner\Data\FileIo;
use SchenkeIo\LaravelUrlCleaner\Data\WebIo;
use SchenkeIo\LaravelUrlCleaner\Makers\Transformer;

it('can find a valid transformer for all cases', function () {
    foreach (Source::cases() as $case) {
        $fileIo = mock(FileIo::class);
        $webIo = mock(WebIo::class);
        expect($case->transformer($fileIo, $webIo))->toBeInstanceOf(Transformer::class);
    }
});

it('has source base and file for all cases', function () {
    foreach (Source::cases() as $case) {
        expect($case->sourceBase() ?? '')->toBeString()
            ->and($case->sourceFile() ?? '')->toBeString();
    }
});

it('can execute a transformer handle', function () {
    $fileIo = mock(FileIo::class);
    $fileIo->shouldReceive('glob')->andReturn([]);
    $fileIo->shouldReceive('put');
    $fileIo->shouldReceive('get')->andReturn('{}');
    $fileIo->shouldReceive('putJson');

    $webIo = mock(WebIo::class);
    $webIo->shouldReceive('get')->andReturn('');

    expect(Source::Marketing00->makeSource($fileIo, $webIo))->toBeString();
});

it('can count the masks', function () {
    $fileIo = mock(FileIo::class);
    $fileIo->shouldReceive('getJson')->andReturn(['' => ['a', 'b', 'c']]);
    expect(Source::Marketing00->maskCount($fileIo))->toBe(3);
});
