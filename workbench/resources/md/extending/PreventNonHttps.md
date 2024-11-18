
### Prevent schemes

Extend `PreventNonHttps` and overwrite the `$allowedSchemes` array with scheme 
you allow to pass.

```php
<?php

use SchenkeIo\LaravelUrlCleaner\Cleaners\PreventNonHttps;

class MyCleaner extends PreventNonHttps {

    protected array $allowedSchemes = [
        'https',
        'http',
        'sftp',
    ];
    
}

```
