<?php

use SchenkeIo\LaravelUrlCleaner\Cleaners\PreventUserPassword;
use SchenkeIo\LaravelUrlCleaner\Data\UrlData;
use SchenkeIo\LaravelUrlCleaner\Exceptions\CleanUrlException;

it('can prevent user password urls', function ($url) {

    $this->expectException(CleanUrlException::class);
    $urlData = new UrlData($url);
    (new PreventUserPassword)->clean($urlData);

})->with([
    'url 1' => ['ftp://user:password@test.com'],
    'url 2' => ['ftp://user@test.com'],
    'url 3' => ['https://user@test.com'],
    'url 4' => ['sftp://user@test.com'],
]);
