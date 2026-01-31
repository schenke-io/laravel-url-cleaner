<?php

use SchenkeIo\LaravelUrlCleaner\Bases\BaseCleaner;
use SchenkeIo\LaravelUrlCleaner\Data\UrlData;

it('remove parameter keys using source returns early when no source found', function () {
    $cleaner = new class extends BaseCleaner
    {
        public function clean(UrlData &$urlData): void
        {
            $this->removeParameterKeysUsingSource($urlData);
        }
    };

    $urlData = new UrlData('https://example.com/?a=1');
    $cleaner->clean($urlData);

    expect($urlData->getUrl())->toBe('https://example.com/?a=1');
});
