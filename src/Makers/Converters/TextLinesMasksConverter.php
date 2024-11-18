<?php

namespace SchenkeIo\LaravelUrlCleaner\Makers\Converters;

use SchenkeIo\LaravelUrlCleaner\Bases\BaseConverter;
use SchenkeIo\LaravelUrlCleaner\Bases\Source;
use SchenkeIo\LaravelUrlCleaner\Data\FileIo;
use SchenkeIo\LaravelUrlCleaner\Data\MaskArray;
use SchenkeIo\LaravelUrlCleaner\Data\MaskTree;
use SchenkeIo\LaravelUrlCleaner\Exceptions\FileIoException;

class TextLinesMasksConverter extends BaseConverter
{
    public function __construct(
        private readonly string $lineRegex,
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
        $lines = explode("\n", $this->fileIo->get($source->pathSourceCopy()));
        $masks = new MaskArray;
        foreach ($lines as $line) {
            if (preg_match($this->lineRegex, $line, $matches)) {
                $masks->add($matches[1]);
            }
        }
        $treeFile = new MaskTree($source->pathFinalJson(), $this->fileIo);
        $treeFile->loadMaskArray($masks);
        $treeFile->store();

        return sprintf('read %s all lines and found %d masks, written to %s', $source->pathSourceCopy(), $masks->count(), $source->pathFinalJson());
    }
}
