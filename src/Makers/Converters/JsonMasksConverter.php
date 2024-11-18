<?php

namespace SchenkeIo\LaravelUrlCleaner\Makers\Converters;

use SchenkeIo\LaravelUrlCleaner\Bases\BaseConverter;
use SchenkeIo\LaravelUrlCleaner\Bases\Source;
use SchenkeIo\LaravelUrlCleaner\Data\FileIo;
use SchenkeIo\LaravelUrlCleaner\Data\MaskTree;
use SchenkeIo\LaravelUrlCleaner\Exceptions\FileIoException;

class JsonMasksConverter extends BaseConverter
{
    public function __construct(
        private readonly string $filter,
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
        $data = $this->fileIo->getJson($source->pathSourceCopy());
        $masks = array_merge(...data_get($data, $this->filter));
        MaskTree::fromMasks($source, $masks, $this->fileIo)->store();

        return sprintf('JSON file %s read, %d masks found and tree written to %s', $source->pathSourceCopy(), count($masks), $source->pathFinalJson());
    }
}
