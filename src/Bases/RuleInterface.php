<?php

namespace SchenkeIo\LaravelUrlCleaner\Bases;

interface RuleInterface
{
    /**
     * made from an external coded string
     */
    public function __construct(string $external);

    /**
     * compares two rules for similarity
     */
    public function isEqual(RuleInterface $otherRule): bool;

    /**
     * checks if this rule fits into the other rule or is same
     */
    public function isIncludedIn(RuleInterface $largerRule): bool;

    /**
     * checks if a given url parameter matches this rule
     */
    public function match(string $value): bool;

    /**
     * returns the original given string back
     */
    public function __toString(): string;

    /**
     * syntax validation
     */
    public static function isValid(string $value): bool;
}
