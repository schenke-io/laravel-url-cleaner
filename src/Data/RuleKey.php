<?php

namespace SchenkeIo\LaravelUrlCleaner\Data;

use SchenkeIo\LaravelUrlCleaner\Bases\RuleInterface;
use SchenkeIo\LaravelUrlCleaner\Exceptions\DefectMaskException;

class RuleKey extends StarHandler implements RuleInterface
{
    /**
     * made from an external coded string
     *
     * @throws DefectMaskException
     */
    public function __construct(string $external)
    {
        if (! self::isValid($external)) {
            throw new DefectMaskException("The key mask '{$external}' is not valid");
        }
        parent::__construct($external);
    }

    /**
     * syntax validation
     */
    public static function isValid(string $value): bool
    {
        return preg_match('@^\*?[.a-z0-9-_]+\*?$@i', $value) === 1;
    }
}
