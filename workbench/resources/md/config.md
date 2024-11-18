
## Config

A default configuration file can be installed and later modified, you can install it with:

```php
php artisan url-cleaner:install
```

A typical result could be:

```php
[
    'cleaners' => [
        MarketingBroad::class,
        RemoveLongValues::class,
        PreventInvalidHost::class
    ],
    'max_length_value' => 40,
    'masks' => ['dd3','vv67'],
    'protected_keys' => ['search']   
]
```

| key              | type  | description                                      | cleaner             |
|------------------|-------|--------------------------------------------------|---------------------|
| cleaners         | array | list of cleaner classes applied to the given URL | any                 |
| max_length_value | int   | values longer than this are removed by           | `RemoveLongValues`  |
| masks            | array | additional masks to be used                      | `RemoveConfigMasks` |
| protected_keys   | array | key names which are guard against removal        | any                 |
