
### Prevent domain names

Extend `PreventLocalhost` and overwrite the `$hostRegExes` array with regular 
expressions matching unwanted hostnames.

```php
<?php

use SchenkeIo\LaravelUrlCleaner\Cleaners\PreventLocalhost;

class MyCleaner extends PreventLocalhost {

    protected array $hostRegExes = [
        '/test\.com/',
        '/test\.net/',
    ];
    
}

```
