<?php

namespace SchenkeIo\LaravelUrlCleaner\Makers;

use SchenkeIo\LaravelUrlCleaner\Bases\Source;
use SchenkeIo\LaravelUrlCleaner\Data\FileIo;
use SchenkeIo\LaravelUrlCleaner\Exceptions\FileIoException;

class TopLevelDomains
{
    public function __construct(protected FileIo $fileIo = new FileIo) {}

    public static function init(FileIo $fileIo = new FileIo): self
    {
        return new self($fileIo);
    }

    /**
     * @throws FileIoException
     */
    public function isInvalidTld($tld): bool
    {
        return ! in_array($tld, $this->fileIo->getJson(Source::TopLevelDomains->pathFinalJson()));
    }
}
