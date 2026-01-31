<?php

namespace SchenkeIo\LaravelUrlCleaner\Exceptions;

/**
 * Exception thrown when invalid scheme exception.
 */
class InvalidSchemeException extends \Exception
{
    /**
     * @param  array<int, string>  $schemes
     */
    public function __construct(string $scheme, array $schemes)
    {
        $message = "Invalid scheme: $scheme. Allowed: ".implode(', ', $schemes);
        try {
            if (function_exists('__')) {
                /** @var string|array<string, string>|null $translated */
                $translated = __('clean_url::exception.invalid_scheme', [
                    'scheme' => $scheme,
                    'schemes' => implode(', ', $schemes),
                ]);
                if (is_string($translated)) {
                    $message = $translated;
                }
            }
        } catch (\Throwable $e) {
            // fallback used
        }
        parent::__construct($message);
    }
}
