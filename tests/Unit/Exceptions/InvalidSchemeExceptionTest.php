<?php

use SchenkeIo\LaravelUrlCleaner\Exceptions\InvalidSchemeException;

it('uses the translation key if translation is missing', function () {
    $exception = new InvalidSchemeException('ftp', ['https']);
    // Since translation is missing, it currently returns the key
    expect($exception->getMessage())->toBe('clean_url::exception.invalid_scheme');
});

it('falls back to default message if translation fails', function () {
    // Mock the translator to throw an exception
    $mockTranslator = Mockery::mock();
    $mockTranslator->shouldReceive('get')->andThrow(new Exception('Translation failed'));

    // Inject the mock into the container
    app()->instance('translator', $mockTranslator);

    $exception = new InvalidSchemeException('ftp', ['https']);
    expect($exception->getMessage())->toBe('Invalid scheme: ftp. Allowed: https');

    // Reset the container for other tests
    app()->forgetInstance('translator');
});

it('falls back if translation returns non-string', function () {
    $mockTranslator = Mockery::mock();
    $mockTranslator->shouldReceive('get')->andReturn(['not a string']);

    app()->instance('translator', $mockTranslator);

    $exception = new InvalidSchemeException('ftp', ['https']);
    expect($exception->getMessage())->toBe('Invalid scheme: ftp. Allowed: https');

    app()->forgetInstance('translator');
});
