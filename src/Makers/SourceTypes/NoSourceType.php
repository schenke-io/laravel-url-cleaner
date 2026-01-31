<?php

namespace SchenkeIo\LaravelUrlCleaner\Makers\SourceTypes;

use SchenkeIo\LaravelUrlCleaner\Bases\BaseSourceType;
use SchenkeIo\LaravelUrlCleaner\Bases\Source;
use SchenkeIo\LaravelUrlCleaner\Data\FileIo;

/**
 * Represents a source type that doesn't actually have an external source to pull from.
 *
 * This is used for composite sources or sources that are already fully defined
 * within the package and don't require external fetching.
 */
class NoSourceType extends BaseSourceType
{
    /**
     * Constructor for NoSourceType.
     *
     * @param  Source  $source  The source instance.
     * @param  FileIo  $fileIo  The FileIo instance.
     */
    public function __construct(protected Source $source, protected FileIo $fileIo = new FileIo)
    {
        parent::__construct($this->fileIo);
    }

    /**
     * Simulation of making a copy, as no external data is involved.
     *
     * @return string A message indicating no data was copied.
     */
    public function makeCopy(): string
    {
        return 'no data copied';

    }

    /**
     * Get the source instance.
     *
     * @return Source The source.
     */
    public function getSource(): Source
    {
        return $this->source;
    }

    /**
     * Get the masks for this source type (empty for NoSourceType).
     *
     * @return array An empty array.
     */
    /**
     * @return array<int, string>
     */
    public function getMasks(): array
    {
        return [];
    }
}
