<?php

namespace SchenkeIo\LaravelUrlCleaner\Data;

use SchenkeIo\LaravelUrlCleaner\Exceptions\CleanUrlException;
use SchenkeIo\LaravelUrlCleaner\Exceptions\DefectMaskException;
use SchenkeIo\LaravelUrlCleaner\Exceptions\InvalidUrlException;

/**
 * Represents the components of a URL and provides methods to manipulate them.
 *
 * This class parses a URL into its constituent parts (scheme, host, port, user, etc.),
 * allows for removal of query parameters based on masks, and can reconstruct
 * the modified URL.
 */
class UrlData
{
    /**
     * @var string The original URL.
     */
    protected string $url = '';

    /**
     * @var string The URL scheme (e.g., http, https).
     */
    public string $scheme = '';

    /**
     * @var string The host name.
     */
    public string $host = '';

    /**
     * @var string The port number.
     */
    public string $port = '';

    /**
     * @var string The username.
     */
    public string $user = '';

    /**
     * @var string The password.
     */
    public string $pass = '';

    /**
     * @var string The URL path.
     */
    public string $path = '';

    /**
     * @var string The query string.
     */
    public string $query = '';

    /**
     * @var string The fragment identifier.
     */
    public string $fragment = '';

    /**
     * @var array<int|string, array<mixed>|string> The parsed query parameters as an associative array.
     */
    public array $parameter = [];

    /**
     * Constructor for UrlData.
     *
     * @param  string  $url  The URL to parse.
     *
     * @throws InvalidUrlException If the given URL is invalid.
     */
    public function __construct(string $url)
    {
        $this->url = $url;

        // check if url is valid
        if (! filter_var($url, FILTER_VALIDATE_URL)) {
            throw new InvalidUrlException('Invalid URL given');
        }
        $data = parse_url($url);

        $this->scheme = $data['scheme'] ?? '';
        $this->host = $data['host'] ?? '';
        $this->port = isset($data['port']) ? (string) $data['port'] : '';
        $this->user = $data['user'] ?? '';
        $this->pass = $data['pass'] ?? '';
        $this->path = $data['path'] ?? '';
        $this->query = $data['query'] ?? '';
        $this->fragment = $data['fragment'] ?? '';

        parse_str($this->query, $this->parameter);
    }

    /**
     * Get the host part of the URL.
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * Get the domain part (same as host in this implementation).
     */
    public function getDomain(): string
    {
        return $this->host;
    }

    /**
     * Get the URL scheme.
     */
    public function getScheme(): string
    {
        return $this->scheme;
    }

    /**
     * Get the URL path.
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Get the query parameter keys.
     *
     * @return array<int, int|string>
     */
    public function getParameterKeys(): array
    {
        return array_keys($this->parameter);
    }

    /**
     * Set the host part of the URL.
     */
    public function setHost(string $host): void
    {
        $this->host = $host;
    }

    /**
     * Set the URL scheme.
     */
    public function setScheme(string $scheme): void
    {
        $this->scheme = $scheme;
    }

    /**
     * Remove parameters matching a specific mask.
     *
     * @param  string  $mask  The mask to match against query parameters.
     *
     * @throws DefectMaskException If the mask is malformed.
     */
    public function removeMask(string $mask): void
    {
        $ruleSet = RuleSet::fromMask($mask);
        if ($ruleSet->ruleDomain->match($this->host)) {
            foreach ($this->parameter as $key => $value) {
                if ($ruleSet->ruleKey->match((string) $key)) {
                    $this->removeParameterKey((string) $key);
                }
            }
        }
    }

    /**
     * Remove a specific query parameter key, unless it's protected.
     *
     * @param  string  $key  The query parameter key to remove.
     */
    public function removeParameterKey(string $key): void
    {
        $protectedKeys = config('url-cleaner.protected_keys') ?? [];
        if (! in_array($key, $protectedKeys)) {
            unset($this->parameter[$key]);
        }
    }

    /**
     * Reconstruct the URL from its current component values.
     *
     * @return string The reconstructed URL.
     *
     * @throws InvalidUrlException If the reconstructed URL is invalid.
     */
    public function getUrl(): string
    {
        // make the query string
        $this->query = http_build_query($this->parameter);
        // Reconstruct the URL
        $returnUrl = $this->fullHost();
        if ($this->path != '') {
            $returnUrl .= $this->path;
        }
        if ($this->query != '') {
            $returnUrl .= '?'.$this->query;
        }
        if ($this->fragment != '') {
            $returnUrl .= '#'.$this->fragment;
        }
        // check if url is valid or throw CleanUrlException
        if (! filter_var($returnUrl, FILTER_VALIDATE_URL)) {
            throw new InvalidUrlException('Invalid URL generated');
        }

        return $returnUrl;
    }

    /**
     * Get the full host string including scheme, user, pass, host, and port.
     *
     * @return string The full host part of the URL.
     */
    public function fullHost(): string
    {
        $returnUrl = strtolower($this->scheme).'://';
        if ($this->user != '') {
            $returnUrl .= $this->user;
            if ($this->pass != '') {
                $returnUrl .= ':'.$this->pass;
            }
            $returnUrl .= '@';
        }

        $returnUrl .= strtolower($this->host);

        if ($this->port != '') {
            $returnUrl .= ':'.$this->port;
        }

        return $returnUrl;
    }
}
