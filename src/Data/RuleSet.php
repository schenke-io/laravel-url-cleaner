<?php

namespace SchenkeIo\LaravelUrlCleaner\Data;

use SchenkeIo\LaravelUrlCleaner\Exceptions\DefectMaskException;

class RuleSet
{
    public RuleKey $ruleKey;

    public RuleDomain $ruleDomain;

    /**
     * @throws DefectMaskException
     */
    public function __construct(public readonly string $key, public readonly string $domain = '')
    {
        if (! RuleKey::isValid($key)) {
            throw new DefectMaskException("Invalid key: $key");
        }
        if (! RuleDomain::isValid($domain)) {
            throw new DefectMaskException("Invalid domain: $domain");
        }
        $this->ruleKey = new RuleKey($key);
        $this->ruleDomain = new RuleDomain($domain);
    }

    /**
     * @throws DefectMaskException
     */
    public static function fromMask(string $mask): self
    {
        $maskParts = explode('@', $mask);
        if (count($maskParts) == 1) {
            return new self($maskParts[0]);
        } elseif (count($maskParts) == 2) {
            return new self($maskParts[0], $maskParts[1]);
        }
        throw new DefectMaskException("Invalid maks: $mask");
    }

    public function string(): string
    {
        return $this->__toString();
    }

    public function __toString(): string
    {
        $return = $this->ruleKey->__toString();
        if ($this->ruleDomain->external) {
            if ($return) {
                $return .= '@';
            }
            $return .= $this->ruleDomain->__toString();
        }

        return $return;
    }

    public function isEqual(RuleSet $otherRuleSet): bool
    {
        return
            $this->ruleDomain->isEqual($otherRuleSet->ruleDomain) &&
            $this->ruleKey->isEqual($otherRuleSet->ruleKey);
    }

    public function isIncludedIn(RuleSet $largerTask): bool
    {
        return
            $this->ruleDomain->isIncludedIn($largerTask->ruleDomain) &&
            $this->ruleKey->isIncludedIn($largerTask->ruleKey);
    }
}
