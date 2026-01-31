<?php

namespace SchenkeIo\LaravelUrlCleaner\Makers;

use GuzzleHttp\Exception\GuzzleException;
use SchenkeIo\LaravelUrlCleaner\Bases\BaseConverter;
use SchenkeIo\LaravelUrlCleaner\Bases\BaseSourceType;
use SchenkeIo\LaravelUrlCleaner\Exceptions\FileIoException;

/**
 * Orchestrates the transformation of source data into final URL masks.
 *
 * This class uses a SourceType to fetch raw data and a Converter to
 * transform it into the final format (e.g., JSON), reporting the progress.
 */
class Transformer
{
    public function __construct(
        private readonly BaseSourceType $sourceType,
        private readonly BaseConverter $converter
    ) {}

    /**
     * @throws FileIoException
     * @throws GuzzleException
     */
    public function handle(): string
    {
        $source = $this->sourceType->getSource();

        return sprintf(
            "**** %s ****\n%s\n%s\n\n",
            $source->name,
            $this->sourceType->makeCopy(),
            $this->converter->convert($source)
        );
    }
}
