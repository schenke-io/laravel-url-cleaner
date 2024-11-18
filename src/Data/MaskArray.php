<?php

namespace SchenkeIo\LaravelUrlCleaner\Data;

use SchenkeIo\LaravelUrlCleaner\Bases\Source;
use SchenkeIo\LaravelUrlCleaner\Exceptions\DefectMaskException;

final class MaskArray
{
    /**
     * @var array<string,RuleSet>
     */
    private array $data = [];

    public function __construct(array $masks = [])
    {
        $this->add($masks);
    }

    public static function fromMasks(array $masks): MaskArray
    {
        return new self($masks);
    }

    /**
     * @return RuleSet[]
     */
    public function ruleSets(): array
    {
        return $this->data;
    }

    /**
     * list of all unique masks
     */
    public function masks(): array
    {
        return array_keys($this->data);
    }

    public function serialize(): string
    {
        return serialize($this->masks());
    }

    public function count(): int
    {
        return count($this->masks());
    }

    public function setKeyValue(string $mask, RuleSet $ruleSet): void
    {
        $this->data[$mask] = $ruleSet;
    }

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

    public function reduce(Source $source): self
    {
        $final = $this->data;
        foreach ($this->data as $thisMask => $thisRuleSet) {
            foreach ($final as $finalMask => $finalRuleSet) {
                if ($thisMask === $finalMask) {
                    continue;
                }
                if ($source === Source::MarketingBroad) {
                    // broad, remove final if smaller
                    if ($finalRuleSet->isIncludedIn($thisRuleSet)) {
                        unset($final[$finalMask]);
                    }
                } elseif ($source === Source::MarketingNarrow) {
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
//
//
//        $keysToDelete = [];
//        $final = MaskArray::fromMasks(array_keys($this->getArrayCopy()));
//        $this->rewind();
//        while ($this->valid()) {
//
//            $final->rewind();
//            while ($final->valid()) {
//                //                 echo sprintf("%s => %s\n",$this->key(), $final->key());
//
//                if ($this->key() !== $final->key()) {
//                    if ($source === Source::MarketingBroad) {
//                        if ($final->current()->isIncludedIn($this->current())) {
//                            echo sprintf("final '%s' included in this '%s\n", $final->current(), $this->current());
//                            $final->offsetUnset($final->key());
//                            $keysToDelete[] = $final->key();
//                        }
//                    } elseif ($source === Source::MarketingNarrow) {
//                        if ($this->current()->isIncludedIn($final->current())) {
//                            echo "this included in final\n";
//                            $final->offsetUnset($final->key());
//                            $keysToDelete[] = $final->key();
//                        }
//                    }
//                }
//                $final->next();
//            }
//            $this->next();
//        }
//
//        /*
//         * reduce $this based on $final
//         */
//        $countBefore = $this->count();
//        foreach ($keysToDelete as $key) {
//            $this->offsetUnset($key);
//        }
//        $countAfter = $this->count();
//        echo sprintf("reduce: from %d to %d keys, keysToDelete: %d\n", $countBefore, $countAfter, $keysToDelete);
//    }

//}
