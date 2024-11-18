<?php

namespace SchenkeIo\LaravelUrlCleaner\Makers\SourceTypes;

use SchenkeIo\LaravelUrlCleaner\Bases\BaseSourceType;
use SchenkeIo\LaravelUrlCleaner\Bases\Source;
use SchenkeIo\LaravelUrlCleaner\Data\FileIo;

class NoSourceType extends BaseSourceType
{
    public function __construct(protected Source $source, protected FileIo $fileIo = new FileIo)
    {
        parent::__construct($this->fileIo);
    }

    public function makeCopy(): string
    {
        return 'no data copied';

    }

    public function getSource(): Source
    {
        return $this->source;
    }

    public function getMasks(): array
    {
        return [];
    }
}
