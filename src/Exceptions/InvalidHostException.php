<?php

namespace SchenkeIo\LaravelUrlCleaner\Exceptions;

class InvalidHostException extends \Exception
{
    public function __construct(protected string $hostName, protected string $technicalMessage)
    {
        parent::__construct(sprintf('Invalid host name: %s', $this->hostName));
    }
}
