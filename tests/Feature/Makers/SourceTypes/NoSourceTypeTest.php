<?php

use SchenkeIo\LaravelUrlCleaner\Bases\Source;
use SchenkeIo\LaravelUrlCleaner\Makers\SourceTypes\NoSourceType;

it('is an mepty source', function () {
    $source = new NoSourceType(Source::Marketing00);
    expect($source->makeCopy())->toBeString()
        ->and($source->getSource())->toBe(Source::Marketing00)
        ->and($source->getMasks())->toBeArray();
});
