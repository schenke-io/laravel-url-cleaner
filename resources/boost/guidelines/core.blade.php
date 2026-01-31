## Overview

Laravel URL Cleaner is a package designed to sanitize and clean URLs by removing unnecessary SEO and tracking parameters. It uses a series of specialized cleaner classes to process URLs, making them shorter and more privacy-friendly.

## Installation

Install the package via composer:

```bash
composer require schenke-io/laravel-url-cleaner
```

Publish the configuration file:

```bash
php artisan url-cleaner:install
```

## Basic Usage

The primary way to use the package is through the `UrlCleaner` class.

@verbatim
<code-snippet name="Clean a URL" lang="php">
use SchenkeIo\LaravelUrlCleaner\UrlCleaner;

$cleaner = new UrlCleaner();
$cleanedUrl = $cleaner->handle('https://example.com/product?id=123&utm_source=twitter&utm_campaign=summer_sale');
// Output: https://example.com/product?id=123
</code-snippet>
@endverbatim

## Customizing Cleaners

You can define which cleaners to apply in the `url-cleaner.php` config file.

@verbatim
<code-snippet name="Using Facade" lang="php">
use SchenkeIo\LaravelUrlCleaner\Facades\UrlCleaner;

$cleanedUrl = UrlCleaner::handle($url);
</code-snippet>
@endverbatim
