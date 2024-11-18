### Rewrite urls

Extend `ShortAmazonProductUrl` and overwrite the `clean()` method using 
the class as an example.

```php
<?php

use SchenkeIo\LaravelUrlCleaner\Cleaners\ShortAmazonProductUrl;

class MyCleaner extends ShortAmazonProductUrl {

    public function clean(UrlData &$urlData): void
    {
        // check if the hostname is right
        if (preg_match(/* regular expression   */, $urlData->host)) {
            // check for the path to be replaced
            if (preg_match(/* regular expression */, $urlData->path, $matches)) {
                
                // your code 

                $urlData->path = /* new path */;
                $urlData->fragment = '';  // clean if applicable
                $urlData->query = '';     // clean if applicable
                $urlData->parameter = []; // clean if applicable
            }
        }
    } 
}

```
