<?php

namespace SchenkeIo\LaravelUrlCleaner\Data;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use SchenkeIo\LaravelUrlCleaner\Exceptions\WebIoException;

/**
 * Handles web input/output operations.
 *
 * This class provides methods to fetch content from web URLs, including
 * specialized support for GitHub raw content.
 */
class WebIo
{
    private readonly Client $client;

    public function __construct(?Client $client = null)
    {
        $this->client = $client ?? new Client;
    }

    /**
     * @throws WebIoException
     */
    public function get(string $url): string
    {
        try {
            $res = $this->client->get($url);
        } catch (GuzzleException $e) {
            throw new WebIoException($e->getMessage(), $e->getCode(), $e);
        }

        //        print_r($res->getHeaders());
        return $res->getBody()->getContents();
    }

    /**
     * @throws WebIoException
     */
    public function getGithub(string $repoBase, string $filePath): string
    {
        return $this->get("https://raw.githubusercontent.com/$repoBase/refs/heads/$filePath");
    }
}
