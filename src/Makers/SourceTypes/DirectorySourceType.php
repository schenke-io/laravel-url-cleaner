<?php

namespace SchenkeIo\LaravelUrlCleaner\Makers\SourceTypes;

use SchenkeIo\LaravelUrlCleaner\Bases\BaseSourceType;
use SchenkeIo\LaravelUrlCleaner\Bases\Source;
use SchenkeIo\LaravelUrlCleaner\Data\FileIo;
use SchenkeIo\LaravelUrlCleaner\Exceptions\FileIoException;

class DirectorySourceType extends BaseSourceType
{
    public function __construct(protected Source $source, protected FileIo $fileIo = new FileIo)
    {
        parent::__construct($this->fileIo);
    }

    /**
     * grab the source data and store it, return a report message
     *
     * @throws FileIoException
     */
    public function makeCopy(): string
    {
        $content = '';
        $files = $this->fileIo->glob($this->source->sourceBase().'/*.txt');
        foreach ($files as $file) {
            $content .= $this->fileIo->get($file);
        }
        $lines = explode("\n", $content);
        $lines = array_filter($lines, function ($line) {
            return trim($line) != '';
        });
        $masks = array_unique($lines);
        sort($masks);
        $content = implode("\n", $masks);
        $this->fileIo->put($this->source->pathSourceCopy(), $content);

        return sprintf(
            'read %d files, with %d lines and %d masks, written to %s',
            count($files),
            count($lines),
            count($masks),
            $this->source->pathSourceCopy()
        );
    }

    public function getSource(): Source
    {
        return $this->source;
    }

    /**
     * @throws FileIoException
     */
    public function getMasks(): array
    {
        return array_filter(
            explode(
                "\n",
                $this->fileIo->get($this->source->pathSourceCopy())
            )
        );
    }
}
