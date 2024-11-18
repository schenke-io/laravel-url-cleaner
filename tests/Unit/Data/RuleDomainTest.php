<?php

use SchenkeIo\LaravelUrlCleaner\Data\RuleDomain;
use SchenkeIo\LaravelUrlCleaner\Exceptions\DefectMaskException;

it('rejects invalid domains', function ($mask) {
    $this->expectException(DefectMaskException::class);
    new RuleDomain($mask);
})->with([
    'dd%%.com',
]);

test('given string is returned', function ($str) {
    expect((string) (new RuleDomain($str)))->toBe($str);
})->with(['aa', 'a*', '*a*']);

test('value matches rule', function ($value, $mask, $match) {
    expect((new RuleDomain($mask))->match($value))->toBe($match);
})->with(
    [
        //            value       mask
        'same' => ['test.com', 'test.com', true],
        'subdomain' => ['www.test.com', '*.test.com', true],
        'domain part' => ['www.test.com', '*.test.*', true],
        'text in domain' => ['www.test-place.com', '*test*', true],
        'any domain' => ['www.test.com', '', true],
    ]
);

test('rules are equal', function ($rule1, $rule2, $similar) {
    $rule1 = new RuleDomain($rule1);
    $rule2 = new RuleDomain($rule2);
    expect($rule1->isEqual($rule2))->toBe($similar);
})->with([
    'same 1' => ['localhost', 'localhost', true],
    'same 2' => ['test.com', 'test.com', true],
    'prefix start ignored 1' => ['*.test.com', '.test.com', false],
    'prefix start ignored 2' => ['*.test.com', 'test.com', false],
]);

test('one rule is included in another', function ($rule1, $rule2, $similar) {
    $rule1 = new RuleDomain($rule1);
    $rule2 = new RuleDomain($rule2);
    expect($rule1->isIncludedIn($rule2))->toBe($similar);

})->with([
    //              ist gleich/Teilmenge von
    'same 1' => ['test.com', 'test.com', true],
    'same 2' => ['*.test.com', '*.test.com', true],
    'larger 1' => ['www.test.com', '*.test.com', true],
    'larger 2' => ['test.*', 'test.com', false],
    'larger 3' => ['*.test.com', '*test*', true],
    'larger 4' => ['*.test.com', '*.com', true],
    'empty 1' => ['*.test.com', '', false],
    'empty 2' => ['test.com', '', true],
    'empty 4' => ['', '*', true],
    'empty 5' => ['*', '**', true],
    'empty 6' => ['*', 'test.com', false],
]);
