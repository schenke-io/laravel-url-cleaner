<?php

namespace SchenkeIo\LaravelUrlCleaner\Makers\SourceTypes;

use SchenkeIo\LaravelUrlCleaner\Bases\BaseSourceType;
use SchenkeIo\LaravelUrlCleaner\Bases\Source;
use SchenkeIo\LaravelUrlCleaner\Data\FileIo;
use SchenkeIo\LaravelUrlCleaner\Data\WebIo;
use SchenkeIo\LaravelUrlCleaner\Exceptions\FileIoException;
use SchenkeIo\LaravelUrlCleaner\Exceptions\MakeException;

class GithubSourceType extends BaseSourceType
{
    public function __construct(
        protected Source $source,
        protected WebIo $webIo = new WebIo,
        protected FileIo $fileIo = new FileIo,
    ) {
        parent::__construct($this->fileIo);
    }

    /**
     * grab the source data and store it, return a report message
     *
     * @throws FileIoException
     * @throws MakeException
     */
    public function makeCopy(): string
    {
        $url = sprintf(
            'https://raw.githubusercontent.com/%s/refs/heads/%s',
            $this->source->sourceBase(),
            $this->source->sourceFile()
        );
        $content = $this->webIo->get($url);
        $this->fileIo->put($this->source->pathSourceCopy(), $content);

        return sprintf(
            'got github %s...%s and store at %s',
            $this->source->sourceBase(),
            $this->source->sourceFile(),
            $this->source->pathSourceCopy()
        );
    }

    public function getSource(): Source
    {
        return $this->source;
    }

    public function getMasks(): array
    {
        return []; // unable to get the masks from filename
    }
}
