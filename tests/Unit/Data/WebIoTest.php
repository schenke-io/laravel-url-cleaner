<?php

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use SchenkeIo\LaravelUrlCleaner\Data\WebIo;
use SchenkeIo\LaravelUrlCleaner\Exceptions\WebIoException;

test('success full get', function () {
    // Mock the Guzzle client
    $client = Mockery::mock(Client::class);
    $client->shouldReceive('get')
        ->with('https://api.example.com/data')
        ->andReturn(new Response(200, [], '123'));

    // Inject the mocked client into your class under test
    $yourClass = new WebIo($client);

    // Call the method you want to test
    $result = $yourClass->get('https://api.example.com/data');

    // Assert the expected behavior
    expect($result)->toBe('123');
});

test('github get', function () {
    // Mock the Guzzle client
    $client = Mockery::mock(Client::class);
    $client->shouldReceive('get')
        ->with('https://raw.githubusercontent.com/a/refs/heads/b')
        ->andReturn(new Response(200, [], '123'));

    // Inject the mocked client into your class under test
    $yourClass = new WebIo($client);

    // Call the method you want to test
    $result = $yourClass->getGithub('a', 'b');

    // Assert the expected behavior
    expect($result)->toBe('123');
});

test('unsuccessful get', function () {
    // Mock the Guzzle client
    $client = Mockery::mock(Client::class);
    $client->shouldReceive('get')
        ->with('https://api.example.com/data')
        ->andThrow(
            new RequestException(
                'Request Failed',
                new Request('GET', 'https://api.example.com/data')
            )
        );

    // Inject the mocked client into your class under test
    $yourClass = new WebIo($client);
    $yourClass->get('https://api.example.com/data');
})->throws(WebIoException::class);
