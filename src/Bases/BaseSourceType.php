<?php

namespace SchenkeIo\LaravelUrlCleaner\Bases;

use GuzzleHttp\Exception\GuzzleException;
use SchenkeIo\LaravelUrlCleaner\Data\FileIo;
use SchenkeIo\LaravelUrlCleaner\Exceptions\FileIoException;

abstract class BaseSourceType
{
    public function __construct(protected FileIo $fileIo = new FileIo) {}

    /**
     * grab the source data and store it, return a report message
     *
     * @return string report the job done
     *
     * @throws FileIoException
     * @throws GuzzleException
     */
    abstract public function makeCopy(): string;

    abstract public function getSource(): Source;

    abstract public function getMasks(): array;
}
