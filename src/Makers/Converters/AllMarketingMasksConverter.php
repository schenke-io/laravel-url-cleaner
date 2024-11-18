<?php

namespace SchenkeIo\LaravelUrlCleaner\Makers\Converters;

use SchenkeIo\LaravelUrlCleaner\Bases\BaseConverter;
use SchenkeIo\LaravelUrlCleaner\Bases\Source;
use SchenkeIo\LaravelUrlCleaner\Data\FileIo;
use SchenkeIo\LaravelUrlCleaner\Data\MaskTree;
use SchenkeIo\LaravelUrlCleaner\Exceptions\FileIoException;

class AllMarketingMasksConverter extends BaseConverter
{
    public function __construct(
        private readonly Source $source,
        protected FileIo $fileIo = new FileIo
    ) {
        parent::__construct($fileIo);
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
        $maskTree = new MaskTree($this->source->pathFinalJson(), $this->fileIo);
        $sourceCount = 0;
        $keyAddedCount = 0;
        foreach (Source::cases() as $case) {
            if ($case->isSourceForAll()) {
                $sourceCount++;
                $keyAddedCount += $maskTree->addFromFile($case->pathFinalJson());
            }
        }
        $maskArray = $maskTree->maskArray();
        $maskArray->reduce($source);
        $afterCount = $maskArray->count();
        $maskTree->delete();
        $maskTree->loadMaskArray($maskArray);
        $maskTree->store();

        return sprintf(
            '%d masks from %d source reduced to %d and written to %s',
            $keyAddedCount,
            $sourceCount,
            $afterCount,
            $this->source->pathFinalJson()
        );
    }
}
