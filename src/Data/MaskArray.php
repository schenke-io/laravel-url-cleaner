<?php

namespace SchenkeIo\LaravelUrlCleaner\Data;

use SchenkeIo\LaravelUrlCleaner\Bases\Source;
use SchenkeIo\LaravelUrlCleaner\Exceptions\DefectMaskException;

/**
 * A collection of URL parameter masks and their corresponding RuleSets.
 *
 * This class provides methods to add, count, serialize, and reduce masks
 * based on their coverage (broad or narrow).
 */
final class MaskArray
{
    /**
     * @var array<string,RuleSet> Internal storage for masks and their RuleSets.
     */
    private array $data = [];

    /**
     * Constructor for MaskArray.
     *
     * @param  array<int|string, string>  $masks  An array of initial masks to add.
     */
    public function __construct(array $masks = [])
    {
        $this->add($masks);
    }

    /**
     * Factory method to create a MaskArray from an array of masks.
     *
     * @param  array<int|string, string>  $masks  An array of masks.
     * @return MaskArray A new instance of MaskArray.
     */
    public static function fromMasks(array $masks): MaskArray
    {
        return new self($masks);
    }

    /**
     * Get all RuleSets in the collection.
     *
     * @return RuleSet[] An array of RuleSet objects.
     */
    public function ruleSets(): array
    {
        return $this->data;
    }

    /**
     * Get a list of all unique masks.
     *
     * @return string[] An array of mask strings.
     */
    public function masks(): array
    {
        return array_keys($this->data);
    }

    /**
     * Serialize the mask list.
     *
     * @return string The serialized array of masks.
     */
    public function serialize(): string
    {
        return serialize($this->masks());
    }

    /**
     * Get the number of masks in the collection.
     *
     * @return int The mask count.
     */
    public function count(): int
    {
        return count($this->masks());
    }

    /**
     * Set a RuleSet for a specific mask.
     *
     * @param  string  $mask  The mask string.
     * @param  RuleSet  $ruleSet  The RuleSet object.
     */
    public function setKeyValue(string $mask, RuleSet $ruleSet): void
    {
        $this->data[$mask] = $ruleSet;
    }

    /**
     * Add one or more masks to the collection.
     *
     * @param  string|array<int|string, string>  $input  A single mask string or an array of masks.
     */
    public function add(string|array $input): void
    {
        if (is_string($input)) {
            try {
                $ruleSet = RuleSet::fromMask($input);
                /*
                 * we catch exception and allow so only clean masks to be added
                 */
                $this->setKeyValue($input, $ruleSet);
            } catch (DefectMaskException $e) {
            }
        } elseif (is_array($input)) {
            foreach ($input as $item) {
                $this->add($item);
            }
        }
    }

    /**
     * Reduce the collection of masks based on a Source's broadness or narrowness.
     *
     * @param  Source  $source  The source defining the reduction strategy.
     * @return self The reduced MaskArray.
     */
    public function reduce(Source $source): self
    {
        $final = $this->data;
        foreach ($this->data as $thisMask => $thisRuleSet) {
            foreach ($final as $finalMask => $finalRuleSet) {
                if ($thisMask === $finalMask) {
                    continue;
                }
                if ($source->name === 'MarketingBroad') {
                    // broad, remove final if smaller
                    if ($finalRuleSet->isIncludedIn($thisRuleSet)) {
                        unset($final[$finalMask]);
                    }
                } elseif ($source->name === 'MarketingNarrow') {
                    // narrow, remove final if larger
                    if ($thisRuleSet->isIncludedIn($finalRuleSet)) {
                        unset($final[$finalMask]);
                    }
                }
            }
        }
        ksort($final);
        $this->data = $final;

        return $this;
    }
}
