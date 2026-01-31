<?php

namespace Tests\Feature\Cleaners;

use Illuminate\Support\Facades\File;
use SchenkeIo\LaravelUrlCleaner\Cleaners;
use SchenkeIo\LaravelUrlCleaner\Data\FileIo;
use SchenkeIo\LaravelUrlCleaner\Data\UrlData;

test('marting cleaners work', function ($class) {
    FileIo::$dataDir = __DIR__.'/../../data';

    // create dummy files if they don't exist to avoid FileIoException
    $source = \SchenkeIo\LaravelUrlCleaner\Bases\Source::tryFrom(class_basename($class));
    if ($source) {
        $path = __DIR__.'/../../data/'.$source->pathFinalJson();
        if (! File::exists(File::dirname($path))) {
            File::makeDirectory(File::dirname($path), 0755, true);
        }
        File::put($path, json_encode(['*' => ['utm_source']]));
    }

    $urlData = new UrlData('https://test.com/?utm_source=1&z=2');
    $cleaner = new $class;
    $cleaner->clean($urlData);

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
