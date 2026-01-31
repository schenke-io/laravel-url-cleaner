<?php

namespace SchenkeIo\LaravelUrlCleaner\Data;

use SchenkeIo\LaravelUrlCleaner\Bases\RuleInterface;

/**
 * Handles wildcard matching for URL parameters and domains.
 *
 * This class supports prefix, suffix, and infix wildcards using the '*' character,
 * providing logic to match values and compare rule inclusion.
 */
class StarHandler implements RuleInterface
{
    public bool $starPrefix;

    public bool $starSuffix;

    public string $maskText;

    public string $external;

    public function __construct(string $external)
    {
        $this->external = $external;
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
     */
    public function isEqual(RuleInterface $otherRule): bool
    {
        return $this->external == $otherRule->__toString();
    }

    /**
     * checks if this rule fits into the other rule or is same
     */
    public function isIncludedIn(RuleInterface $largerRule): bool
    {
        if (! ($largerRule instanceof StarHandler)) {
            return false;
        }

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

    /**
     * syntax validation - dummy implementation for StarHandler
     */
    public static function isValid(string $value): bool
    {
        return true;
    }
}
