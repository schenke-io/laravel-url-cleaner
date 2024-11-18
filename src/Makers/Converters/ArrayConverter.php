<?php

namespace SchenkeIo\LaravelUrlCleaner\Makers\Converters;

use SchenkeIo\LaravelUrlCleaner\Bases\BaseConverter;
use SchenkeIo\LaravelUrlCleaner\Bases\Source;
use SchenkeIo\LaravelUrlCleaner\Data\FileIo;
use SchenkeIo\LaravelUrlCleaner\Exceptions\FileIoException;

class ArrayConverter extends BaseConverter
{
    public function __construct(
        private readonly string $lineRegex,
        protected FileIo $fileIo = new FileIo,
        protected bool $skipComments = false,
        protected bool $toLower = false
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
        $data = [];
        foreach (explode("\n", $content) as $line) {
            $line = trim($line);
            if ($this->skipComments) {
                if (str_starts_with($line, '#')) {
                    continue;
                }
            }
            if (preg_match($this->lineRegex, $line)) {
                if ($this->toLower) {
                    $line = mb_strtolower($line);
                }
                $data[] = $line;
            }
        }
        $this->fileIo->putJson($source->pathFinalJson(), $data);

        return sprintf('JSON file %s read, %d items found and array written to %s',
            $source->pathSourceCopy(), count($data), $source->pathFinalJson()
        );
    }
}
