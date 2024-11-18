<?php

namespace SchenkeIo\LaravelUrlCleaner\Exceptions;

class CleanUrlException extends \Exception
{
    public function __construct($message)
    {
        parent::__construct($message, 0);
    }
}
