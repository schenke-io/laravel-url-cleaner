<?php

namespace SchenkeIo\LaravelUrlCleaner\Tests\Data;

use SchenkeIo\LaravelUrlCleaner\Bases\Source;
use SchenkeIo\LaravelUrlCleaner\Data\MaskArray;

test('invalid maks get not used', function () {
    $maskArray = MaskArray::fromMasks(['a', 'b', 'c', 'hello world']);
    expect($maskArray->count())->toBe(3);
});

test('broad list merging', function ($givenSet, $expectedSet) {
    $reducedSet = MaskArray::fromMasks($givenSet)->reduce(Source::MarketingBroad);
    $expectedSet = MaskArray::fromMasks($expectedSet);

    expect($reducedSet->serialize())->toBe($expectedSet->serialize());
})->with([

    // simple filtering of similar values
    'single' => [['a'], ['a']],
    'double' => [['a', 'a'], ['a']],
    'triple' => [['a', 'a', 'a'], ['a']],
    // sorting
    'pair' => [['a', 'b'], ['a', 'b']],
    'sorted pair' => [['b', 'a'], ['a', 'b']],
    // the broader term survives

    'broader wins 1' => [['a', 'a*'], ['a*']],
    'broader wins 2' => [['a', 'a@test.com'], ['a']],
    'broader wins 3' => [['a@test.com', 'a'], ['a']],
    'broader wins 4' => [['a*', 'a@test.com'], ['a*']],
]);

test('narrow list merging', function ($givenSet, $expectedSet) {
    $reducedSet = MaskArray::fromMasks($givenSet)->reduce(Source::MarketingNarrow);
    $expectedSet = MaskArray::fromMasks($expectedSet);

    expect($reducedSet->serialize())->toBe($expectedSet->serialize());
})->with([
    // simple filtering of similar values
    'single' => [['a'], ['a']],
    'double' => [['a', 'a'], ['a']],
    'triple' => [['a', 'a', 'a'], ['a']],
    // sorting
    'pair' => [['a', 'b'], ['a', 'b']],
    'sorted pair' => [['b', 'a'], ['a', 'b']],
    // the narrower term survives
    'narrow wins 1' => [['a', 'a*'], ['a']],
    'narrow wins 2' => [['a', 'a@test.com'], ['a@test.com']],
    'narrow wins 3' => [['a@test.com', 'a'], ['a@test.com']],
    'narrow wins 4' => [['a*', 'a@test.com'], ['a@test.com']],
]);
