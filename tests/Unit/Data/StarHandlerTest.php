<?php

use SchenkeIo\LaravelUrlCleaner\Data\StarHandler;

test('it can match with prefix star', function () {
    $handler = new StarHandler('*abc');
    expect($handler->match('testabc'))->toBeTrue()
        ->and($handler->match('abctest'))->toBeFalse();
});

test('it can match with suffix star', function () {
    $handler = new StarHandler('abc*');
    expect($handler->match('abctest'))->toBeTrue()
        ->and($handler->match('testabc'))->toBeFalse();
});

test('it can match with both stars', function () {
    $handler = new StarHandler('*abc*');
    expect($handler->match('testabctest'))->toBeTrue()
        ->and($handler->match('xyz'))->toBeFalse();
});

test('it can match without stars', function () {
    $handler = new StarHandler('abc');
    expect($handler->match('abc'))->toBeTrue()
        ->and($handler->match('abctest'))->toBeFalse();
});

test('it can match empty mask (allows all)', function () {
    $handler = new StarHandler('');
    expect($handler->match('anything'))->toBeTrue();
});

test('isEqual works', function () {
    $handler1 = new StarHandler('*abc');
    $handler2 = new StarHandler('*abc');
    $handler3 = new StarHandler('abc*');

    // We need an object that implements RuleInterface, but StarHandler itself doesn't
    // although it is used as a base. RuleKey and RuleDomain implement it.
    // For now we test with StarHandler if it works (even if typehint says RuleInterface)
    expect($handler1->isEqual($handler2))->toBeTrue()
        ->and($handler1->isEqual($handler3))->toBeFalse();
});

test('isIncludedIn works', function () {
    $handler1 = new StarHandler('abc');
    $handler2 = new StarHandler('*abc*');
    $handler3 = new StarHandler('ab');

    expect($handler1->isIncludedIn($handler2))->toBeTrue()
        ->and($handler2->isIncludedIn($handler1))->toBeFalse();

    // Additional coverage for isIncludedIn
    $handlerSuffix = new StarHandler('abc*');
    $handlerPrefix = new StarHandler('*abc');
    $handlerBoth = new StarHandler('*abc*');

    // suffix and !larger->suffix
    expect($handlerSuffix->isIncludedIn($handler1))->toBeFalse();
    // prefix and !larger->prefix
    expect($handlerPrefix->isIncludedIn($handler1))->toBeFalse();

    // largerRule->maskText == ''
    $handlerEmpty = new StarHandler('');
    expect($handler1->isIncludedIn($handlerEmpty))->toBeTrue();

    // non StarHandler
    $nonStarHandler = new class('x') implements \SchenkeIo\LaravelUrlCleaner\Bases\RuleInterface
    {
        public function __construct(string $external) {}

        public function match(string $value): bool
        {
            return true;
        }

        public function isEqual(\SchenkeIo\LaravelUrlCleaner\Bases\RuleInterface $otherRule): bool
        {
            return true;
        }

        public function isIncludedIn(\SchenkeIo\LaravelUrlCleaner\Bases\RuleInterface $largerRule): bool
        {
            return true;
        }

        public function __toString(): string
        {
            return '';
        }

        public static function isValid(string $value): bool
        {
            return true;
        }
    };
    expect($handler1->isIncludedIn($nonStarHandler))->toBeFalse();
});

test('isValid works', function () {
    expect(StarHandler::isValid('anything'))->toBeTrue();
});
