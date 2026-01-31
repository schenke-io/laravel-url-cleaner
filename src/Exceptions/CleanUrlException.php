<?php

namespace SchenkeIo\LaravelUrlCleaner\Exceptions;

/**
 * Exception thrown when clean url exception.
 */
class CleanUrlException extends \Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message, 0);
    }
}
