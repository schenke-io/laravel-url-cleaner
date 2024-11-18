<?php

use SchenkeIo\LaravelUrlCleaner\Bases\Source;
use SchenkeIo\LaravelUrlCleaner\Data\FileIo;
use SchenkeIo\LaravelUrlCleaner\Data\WebIo;
use SchenkeIo\LaravelUrlCleaner\Makers\Transformer;

it('can find a valid transformer for all cases', function (Source $case) {
    $fileIo = Mockery::mock(FileIo::class);
    $webIo = Mockery::mock(WebIo::class);
    expect($case->transformer($fileIo, $webIo))->toBeInstanceOf(Transformer::class);
})->with(Source::cases());

it('has source base and file for all cases', function (Source $case) {
    expect($case->sourceBase() ?? '')->toBeString();
})->with(Source::cases());

it('can execute a transformer handle()', function () {
    $fileIo = Mockery::mock(FileIo::class);
    $fileIo->shouldReceive('glob');
    $fileIo->shouldReceive('put');
    $fileIo->shouldReceive('get');
    $fileIo->shouldReceive('putJson');

    $webIo = Mockery::mock(WebIo::class);

    expect(Source::Marketing00->makeSource($fileIo, $webIo))->toBeString();

});

it('can count the masks', function () {
    $fileIo = Mockery::mock(FileIo::class);
    $fileIo->shouldReceive('getJson')->andReturn(['' => ['a', 'b', 'c']]);
    expect(Source::Marketing00->maskCount($fileIo))->toBe(3);
});
