<?php

namespace SchenkeIo\LaravelUrlCleaner\Makers\Converters;

use SchenkeIo\LaravelUrlCleaner\Bases\BaseConverter;
use SchenkeIo\LaravelUrlCleaner\Bases\Source;
use SchenkeIo\LaravelUrlCleaner\Data\FileIo;
use SchenkeIo\LaravelUrlCleaner\Data\MaskTree;
use SchenkeIo\LaravelUrlCleaner\Exceptions\FileIoException;

class TextLineMasksConverter extends BaseConverter
{
    public function __construct(
        private readonly string $delimiter,
        protected FileIo $fileIo = new FileIo
    ) {
        parent::__construct($this->fileIo);
    }

    /**
     * convert the local data into the main json format, report true if success
     *
     * @return string report the job done
     *
     * @throws FileIoException
     */
    public function convert(Source $source): string
    {
        $content = $this->fileIo->get($source->pathSourceCopy());
        if ($this->delimiter === '') {
            $masks = [$content];
        } else {
            $masks = explode($this->delimiter, $content);
        }
        MaskTree::fromMasks($source, $masks, $this->fileIo)->store();

        return sprintf('read %s by line and found %d masks, written to %s', $source->pathSourceCopy(), count($masks), $source->pathFinalJson());
    }
}
