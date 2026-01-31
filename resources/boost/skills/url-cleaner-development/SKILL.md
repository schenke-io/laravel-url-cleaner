---
name: url-cleaner-development
description: Build and work with Laravel URL Cleaner features, including creating custom cleaners and managing URL sanitization logic.
---

## When to use
Use this skill when you need to:
- Extend the URL cleaning logic by creating new cleaner classes.
- Configure existing cleaners to handle specific URL parameters or tracking data.
- Integrate URL cleaning into Laravel applications using the provided Facade or Service Container.
- Troubleshoot or optimize the URL sanitization process.

## Features

### Creating Custom Cleaners
You can create new cleaners by extending the `BaseCleaner` class. Custom cleaners allow for domain-specific or parameter-specific logic that isn't covered by the default cleaners.

```php
use SchenkeIo\LaravelUrlCleaner\Bases\BaseCleaner;
use SchenkeIo\LaravelUrlCleaner\Data\UrlData;

class MyCustomCleaner extends BaseCleaner
{
    public function clean(UrlData &$urlData): void
    {
        // Custom logic to modify $urlData->query
    }
}
```

### Configuration-Driven Sanitization
The package is highly configurable. You can enable or disable cleaners, set maximum value lengths, and define protected keys that should never be removed.

### Facade Support
The package provides a convenient `UrlCleaner` facade for easy integration and mocking in tests.

```php
use SchenkeIo\LaravelUrlCleaner\Facades\UrlCleaner;

$url = UrlCleaner::handle('https://example.com/?bad_param=1');
```
