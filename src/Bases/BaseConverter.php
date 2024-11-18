<?php

namespace SchenkeIo\LaravelUrlCleaner\Bases;

use SchenkeIo\LaravelUrlCleaner\Data\FileIo;
use SchenkeIo\LaravelUrlCleaner\Exceptions\FileIoException;

abstract class BaseConverter
{
    public function __construct(protected FileIo $fileIo = new FileIo) {}

    /**
     * convert the local data into the main json format, report true if success
     *
     * @return string report the job done
     *
     * @throws FileIoException
     */
    abstract public function convert(Source $source): string;
}
