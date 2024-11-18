<?php

namespace SchenkeIo\LaravelUrlCleaner\Makers\SourceTypes;

use SchenkeIo\LaravelUrlCleaner\Bases\BaseSourceType;
use SchenkeIo\LaravelUrlCleaner\Bases\Source;
use SchenkeIo\LaravelUrlCleaner\Data\FileIo;
use SchenkeIo\LaravelUrlCleaner\Data\WebIo;
use SchenkeIo\LaravelUrlCleaner\Exceptions\FileIoException;
use SchenkeIo\LaravelUrlCleaner\Exceptions\MakeException;

class WebSourceType extends BaseSourceType
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
        $url = $this->source->sourceFile();
        $content = $this->webIo->get($url);
        $this->fileIo->put($this->source->pathSourceCopy(), $content);

        return sprintf(
            'got %s with %d lines and store at %s',
            $url,
            count(explode("\n", $content)),
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
