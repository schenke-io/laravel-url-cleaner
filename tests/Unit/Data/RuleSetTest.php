<?php

use SchenkeIo\LaravelUrlCleaner\Data\RuleSet;
use SchenkeIo\LaravelUrlCleaner\Exceptions\DefectMaskException;

it('can make a RuleSet from external', function ($external, $expected) {
    expect(RuleSet::fromMask($external)->string())->toBe($expected);
})->with([
    'simple 1' => ['a', 'a'],
    'simple 2' => ['a@test.com', 'a@test.com'],
    'star 1' => ['a*', 'a*'],
    'star 2' => ['ab*', 'ab*'],
    'domain 1' => ['a@test.*', 'a@test.*'],
    'domain 2' => ['a@*.test.com', 'a@*.test.com'],
]);

it('see exception when invalid keys come', function ($mask) {
    $this->expectException(DefectMaskException::class);
    RuleSet::fromMask($mask);
})->with([' a  b', '1@2@3', 'aa@d%%%.com']);

test('two rule-sets are equal', function ($term1, $term2, $isEqual) {
    $ruleSet1 = RuleSet::fromMask($term1);
    $ruleSet2 = RuleSet::fromMask($term2);
    expect($ruleSet1->isEqual($ruleSet2))->toBe($isEqual);
})->with([
    'simple 1' => ['a', 'a', true],
    'simple 2' => ['a', 'b', false],
]);

test('one rule is included in another', function ($term1, $term2, $isEqual) {
    $ruleSet1 = RuleSet::fromMask($term1);
    $ruleSet2 = RuleSet::fromMask($term2);
    expect($ruleSet1->isIncludedIn($ruleSet2))->toBe($isEqual);
})->with([
    'same 1' => ['a', 'a', true],
    'same 2' => ['a*', 'a*', true],
    'same 3' => ['a@tes.com', 'a@tes.com', true],
    'starts with 1' => ['a', 'a*', true],
    'starts with 2' => ['abc.-_', 'a*', true],
    'starts with 3' => ['a._bc.-_', 'a.*', true],
    'combination 1' => ['a@test.com', 'a*', true],
    'combination 2' => ['a@test.com', 'a', true],
    'domain 1' => ['a@test.com', 'a@test.*', true],
    'domain 2' => ['a@test.com', 'a@*.com', true],
]);
