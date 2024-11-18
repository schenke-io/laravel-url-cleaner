### Use your own masks

Extend `RemoveSearch` and overwrite the `$masks` array with masks you want to exclude.

```php
<?php

use SchenkeIo\LaravelUrlCleaner\Cleaners\RemoveSearch;

class MyCleaner extends RemoveSearch {

    protected array $masks = [
        'utm_*',
        'test*',
        'q@test.net'
    ];
    
}

```
