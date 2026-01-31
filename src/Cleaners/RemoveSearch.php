<?php

namespace SchenkeIo\LaravelUrlCleaner\Cleaners;

use SchenkeIo\LaravelUrlCleaner\Bases\BaseCleaner;
use SchenkeIo\LaravelUrlCleaner\Data\UrlData;
use SchenkeIo\LaravelUrlCleaner\Exceptions\DefectMaskException;

/**
 * @short remove typical search parameters
 *
 * @task parameter_removal_by_mask
 */
class RemoveSearch extends BaseCleaner
{
    /** @var array<int, string> */
    protected array $masks = ['q', 'search'];

    /**
     * cleans the UrlData object
     *
     * @throws DefectMaskException
     */
    public function clean(UrlData &$urlData): void
    {
        foreach ($this->masks as $mask) {
            $urlData->removeMask($mask);
        }
    }
}
