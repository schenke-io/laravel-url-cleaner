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

test('smaller fits into larger rules', function ($ruleSmall, $ruleLarge, $isInside) {
    $ruleSmall = new RuleKey($ruleSmall);
    $ruleLarge = new RuleKey($ruleLarge);
    expect($ruleSmall->isIncludedIn($ruleLarge))->toBe($isInside);
})->with([
    'same 1' => ['aa', 'aa', true],
    'same 2' => ['a__._', 'a__._', true],
    'fix in start with 1' => ['a', 'a*', true],
    'fix in start with 2' => ['ab', 'a*', true],
    'fix in start with 3' => ['abc', 'a*', true],
    'fix in start with 4' => ['abc', 'aa*', false],
]);
