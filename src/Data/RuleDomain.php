<?php

namespace SchenkeIo\LaravelUrlCleaner\Data;

use SchenkeIo\LaravelUrlCleaner\Bases\RuleInterface;
use SchenkeIo\LaravelUrlCleaner\Exceptions\DefectMaskException;

class RuleDomain extends StarHandler implements RuleInterface
{
    /**
     * made from an external coded string
     *
     * @throws DefectMaskException
     */
    public function __construct(string $external)
    {
        if (! $this->isValid($external)) {
            throw new DefectMaskException("The domain mask '{$external}' is not valid");
        }

        parent::__construct($external);
    }

    /**
     * syntax validation
     */
    public static function isValid(string $value): bool
    {
        return preg_match('/^\*?[.-_\w]*\*?$/', $value) === 1;
    }
}
