<?php

namespace SchenkeIo\LaravelUrlCleaner\Bases;

use SchenkeIo\LaravelUrlCleaner\Data\FileIo;
use SchenkeIo\LaravelUrlCleaner\Data\MaskTree;
use SchenkeIo\LaravelUrlCleaner\Data\UrlData;
use SchenkeIo\LaravelUrlCleaner\Exceptions\DefectMaskException;
use SchenkeIo\LaravelUrlCleaner\Exceptions\FileIoException;

abstract class BaseCleaner
{
    public function __construct(protected FileIo $fileIo = new FileIo) {}

    /**
     * cleans the UrlData object
     *
     * @throws DefectMaskException
     */
    abstract public function clean(UrlData &$urlData): void;

    /**
     * @throws FileIoException
     * @throws DefectMaskException
     */
    protected function removeParameterKeysUsingSource(UrlData &$urlData): void
    {
        $source = Source::fromClass($this);
        $keysToRemove = MaskTree::fromSource($source, $this->fileIo)->getKeysToRemove($urlData);
        foreach ($keysToRemove as $key) {
            $urlData->removeParameterKey($key);
        }
    }
}
