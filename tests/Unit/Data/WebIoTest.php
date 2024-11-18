<?php

namespace SchenkeIo\LaravelUrlCleaner\Tests\Data;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Mockery;
use PHPUnit\Framework\TestCase;
use SchenkeIo\LaravelUrlCleaner\Data\WebIo;
use SchenkeIo\LaravelUrlCleaner\Exceptions\WebIoException;

class WebIoTest extends TestCase
{
    public function testSuccessFullGet()
    {
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
        $this->assertEquals('123', $result);
    }

    public function testGithubGet()
    {
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
        $this->assertEquals('123', $result);
    }

    public function testUnsuccessfulGet()
    {
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
        $this->expectException(WebIoException::class);
        $yourClass->get('https://api.example.com/data');
    }
}
