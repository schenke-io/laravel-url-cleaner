<?php

namespace SchenkeIo\LaravelUrlCleaner\Data;

use SchenkeIo\LaravelUrlCleaner\Bases\RuleInterface;

class StarHandler
{
    public readonly bool $starPrefix;

    public readonly bool $starSuffix;

    public readonly string $maskText;

    public function __construct(public readonly string $external)
    {
        $this->starPrefix = str_starts_with($this->external, '*');
        $this->starSuffix = str_ends_with($this->external, '*');
        $this->maskText = trim($external, '*');
    }

    /**
     * checks if a given url parameter matches this rule
     */
    public function match(string $value): bool
    {
        if (strlen($this->maskText) == 0) {
            // an empty masks allows all, happens only with domains
            return true;
        } elseif ($this->starSuffix) {
            if ($this->starPrefix) {
                // *MASK*
                return str_contains($value, $this->maskText);
            } else {
                // MASK*
                return str_starts_with($value, $this->maskText);  // hay needle
            }
        } else {
            if ($this->starPrefix) {
                // *MASK
                return str_ends_with($value, $this->maskText);
            } else {
                // MASK
                return $value == $this->maskText;
            }
        }
    }

    /**
     * compares two rules for similarity
     *
     * @param  RuleDomain  $otherRule
     */
    public function isEqual(RuleInterface $otherRule): bool
    {
        return $this->external == $otherRule->external;
    }

    /**
     * checks if this rule fits into the other rule or is same
     *
     * @param  RuleDomain  $largerRule
     */
    public function isIncludedIn(RuleInterface $largerRule): bool
    {
        /*
         * if this mask has a star more than the larger rule
         * it can not be contained in it
         */
        if (
            ($this->starSuffix && ! $largerRule->starSuffix)
            ||
            ($this->starPrefix && ! $largerRule->starPrefix)
        ) {
            return false;
        } else {
            if ($largerRule->maskText == '') {
                // when in domain empty strings means any
                return true;
            } else {
                return str_contains($this->maskText, $largerRule->maskText);  // hey,needle
            }
        }
    }

    /**
     * returns the original given string back
     */
    public function __toString(): string
    {
        return $this->external;
    }
}
