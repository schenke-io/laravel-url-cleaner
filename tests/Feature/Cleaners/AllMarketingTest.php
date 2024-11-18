<?php

use SchenkeIo\LaravelUrlCleaner\Cleaners;
use SchenkeIo\LaravelUrlCleaner\Data\FileIo;
use SchenkeIo\LaravelUrlCleaner\Data\UrlData;

test('marting cleaners work', function ($class) {
    $fileIo = Mockery::mock(FileIo::class);
    $fileIo->shouldReceive('getJson')->once()->andReturn([
        'test.com' => ['a', 'b', 'c'],
    ]);

    $urlData = new UrlData('https://test.com/?a=1&z=2');
    (new $class($fileIo))->clean($urlData);
    expect($urlData->getUrl())->toBe('https://test.com/?z=2');

})->with([
    Cleaners\Marketing00::class,
    Cleaners\Marketing01::class,
    Cleaners\Marketing02::class,
    Cleaners\Marketing03::class,
    Cleaners\Marketing04::class,
    Cleaners\MarketingUnique::class,
    Cleaners\MarketingNarrow::class,
    Cleaners\MarketingBroad::class,
]);
