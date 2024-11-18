<?php

namespace SchenkeIo\LaravelUrlCleaner\Exceptions;

class InvalidSchemeException extends \Exception
{
    public function __construct(string $scheme, array $schemes)
    {
        parent::__construct(
            __(
                'clean_url::exception.invalid_scheme',
                [
                    'scheme' => $scheme,
                    'schemes' => implode(', ', $schemes),
                ]
            )
        );
    }
}
