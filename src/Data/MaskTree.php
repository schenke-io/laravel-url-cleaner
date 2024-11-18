<?php

namespace SchenkeIo\LaravelUrlCleaner\Data;

use SchenkeIo\LaravelUrlCleaner\Bases\Source;
use SchenkeIo\LaravelUrlCleaner\Exceptions\DefectMaskException;
use SchenkeIo\LaravelUrlCleaner\Exceptions\FileIoException;

final class MaskTree
{
    private array $tree = [];

    public function __construct(
        public readonly string $filePath,
        protected FileIo $fileIo = new FileIo
    ) {}

    /**
     * used when the url is finally cleaned
     *
     * @throws FileIoException
     */
    public static function fromSource(
        Source $source,
        FileIo $fileIo = new FileIo
    ): self {
        $me = new self($source->pathFinalJson(), $fileIo);
        $me->addFromFile($source->pathFinalJson());

        return $me;
    }

    public static function fromMasks(
        Source $source,
        array $masks,
        FileIo $fileIo = new FileIo
    ): self {
        $me = new self($source->pathFinalJson(), $fileIo);
        $me->loadMaskArray(MaskArray::fromMasks($masks));

        return $me;
    }

    /**
     * mainly used in making final data
     *
     * @return int count of keys added before claening
     *
     * @throws FileIoException
     */
    public function addFromFile(string $filePath): int
    {
        $return = 0;
        foreach ($this->fileIo->getJson($filePath) as $domain => $keys) {
            $return += count($keys);
            $this->tree[$domain] = array_merge($this->tree[$domain] ?? [], $keys);
        }
        $this->clean();

        return $return;
    }

    private function clean(): void
    {
        ksort($this->tree);
        foreach ($this->tree as $domain => $keys) {
            $keys = array_values(array_unique($keys));
            sort($keys);
            $this->tree[$domain] = $keys;
        }
    }

    /**
     * fast process for the real time use in cleaners
     *
     * @throws FileIoException
     */
    public function load(): void
    {
        $this->clean();
        $this->addFromFile($this->filePath);
    }

    public function loadMaskArray(MaskArray $maskArray): void
    {
        foreach ($maskArray->ruleSets() as $mask => $ruleSet) {
            $this->tree[$ruleSet->domain][] = $ruleSet->key;
        }
        $this->clean();
    }

    /**
     * @throws FileIoException
     */
    public function store(): void
    {
        $this->fileIo->putJson($this->filePath, $this->tree);
    }

    public function delete(): void
    {
        $this->tree = [];
    }

    /**
     * @throws DefectMaskException
     */
    public function getKeysToRemove(UrlData $urlData): array
    {
        $return = [];
        foreach ($urlData->parameter as $queryKey => $queryValue) {
            // we only loop if the url has query parameters
            foreach ($this->tree as $domain => $keys) {
                if ((new RuleDomain($domain))->match($urlData->host)) {
                    foreach ($keys as $key) {
                        if ((new RuleKey($key))->match($queryKey)) {
                            $return[] = $queryKey;
                        }
                    }
                }
            }
        }

        return $return;
    }

    public function maskArray(): MaskArray
    {
        $masks = [];
        foreach ($this->tree as $domain => $keys) {
            foreach ($keys as $key) {
                $mask = $domain ? "$key@$domain" : $key;
                $masks[] = $mask;
            }
        }

        return MaskArray::fromMasks($masks);
    }
}
