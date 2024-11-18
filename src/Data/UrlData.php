<?php

namespace SchenkeIo\LaravelUrlCleaner\Data;

use SchenkeIo\LaravelUrlCleaner\Exceptions\CleanUrlException;
use SchenkeIo\LaravelUrlCleaner\Exceptions\DefectMaskException;
use SchenkeIo\LaravelUrlCleaner\Exceptions\InvalidUrlException;

class UrlData
{
    protected string $url = '';

    public string $scheme = '';

    public string $host = '';

    public string $port = '';

    public string $user = '';

    public string $pass = '';

    public string $path = '';

    public string $query = '';

    public string $fragment = '';

    public array $parameter = [];

    /**
     * @throws InvalidUrlException
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
        $this->port = $data['port'] ?? '';
        $this->user = $data['user'] ?? '';
        $this->pass = $data['pass'] ?? '';
        $this->path = $data['path'] ?? '';
        $this->query = $data['query'] ?? '';
        $this->fragment = $data['fragment'] ?? '';

        parse_str($this->query, $this->parameter);
    }

    /**
     * @throws DefectMaskException
     */
    public function removeMask(string $mask): void
    {
        $ruleSet = RuleSet::fromMask($mask);
        if ($ruleSet->ruleDomain->match($this->host)) {
            foreach ($this->parameter as $key => $value) {
                if ($ruleSet->ruleKey->match($key)) {
                    $this->removeParameterKey($key);
                }
            }
        }
    }

    public function removeParameterKey(string $key): void
    {
        if (! in_array($key, config('url-cleaner.protected_keys') ?? [])) {
            unset($this->parameter[$key]);
        }
    }

    /**
     * @throws InvalidUrlException
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
