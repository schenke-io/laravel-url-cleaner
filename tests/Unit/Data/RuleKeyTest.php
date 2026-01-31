<?php

use SchenkeIo\LaravelUrlCleaner\Data\RuleKey;
use SchenkeIo\LaravelUrlCleaner\Exceptions\DefectMaskException;

test('given string is returned', function ($str) {
    expect((string) (new RuleKey($str)))->toBe($str);
})->with(['aa', 'a*', '0123x*']);

test('invalid keys are rejected', function ($key) {
    $this->expectException(DefectMaskException::class);
    new RuleKey($key);
})->with(['& &', 'a b c']);

test('value matches rule', function ($value, $rule, $match) {
    expect((new RuleKey($rule))->match($value))->toBe($match);
})->with(
    [
        'same 1' => ['aa', 'aa', true],
        'same 2' => ['a__._', 'a__._', true],
        'start 1' => ['aa', 'a*', true],
        'start 2' => ['aa', 'aa*', true],
        'start 3' => ['aa', 'aaa*', false],
    ]
);

test('rules are seen equal', function ($rule1, $rule2, $isEqual) {
    $rule1 = new RuleKey($rule1);
    $rule2 = new RuleKey($rule2);
    expect($rule1->isEqual($rule2))->toBe($isEqual);
})->with([
    'same' => ['ab*', 'ab*', true],
]);

test('isValid returns correct result', function ($key, $expected) {
    expect(RuleKey::isValid($key))->toBe($expected);
})->with([
    ['aa', true],
    ['a*', true],
    ['& &', false],
]);

test('isIncludedIn with non StarHandler returns false', function () {
    $rule = new RuleKey('aa');
    $other = new class implements \SchenkeIo\LaravelUrlCleaner\Bases\RuleInterface
    {
        public function __construct(string $external = '') {}

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
    expect($rule->isIncludedIn($other))->toBeFalse();
});
