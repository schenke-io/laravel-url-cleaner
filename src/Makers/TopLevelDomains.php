<?php

namespace SchenkeIo\LaravelUrlCleaner\Makers;

use SchenkeIo\LaravelUrlCleaner\Data\FileIo;
use SchenkeIo\LaravelUrlCleaner\Exceptions\FileIoException;

/**
 * Manages the validation of Top-Level Domains (TLDs).
 *
 * This class provides methods to check if a given TLD is valid based
 * on the list of TLDs fetched from IANA.
 */
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
    public function isInvalidTld(string $tld): bool
    {
        /** @var array<int, string> $tldList */
        $tldList = $this->fileIo->getJson('final/topleveldomains.json');

        return ! in_array($tld, $tldList);
    }
}
