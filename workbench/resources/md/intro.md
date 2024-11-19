
# Laravel URL cleaner - clean and concise

[![Latest Version on Packagist](https://img.shields.io/packagist/v/schenke-io/laravel-url-cleaner.svg?style=plastic)](https://packagist.org/packages/schenke-io/laravel-url-cleaner)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/schenke-io/laravel-url-cleaner/run-tests.yml?branch=main&label=tests&style=plastic)](https://github.com/schenke-io/laravel-relation-manager/actions?query=workflow%3Arun-tests+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/schenke-io/laravel-url-cleaner.svg?style=plastic)](https://packagist.org/packages/schenke-io/laravel-url-cleaner)
![](/.github/coverage-badge.svg)

![](/.github/clean-url.png)

The Laravel URL Cleaner package sanitizes URLs by removing 
unnecessary SEO parameters, tracking information, and other 
clutter, ensuring clean and efficient URL handling in 
your Laravel applications.

To install just run:

      composer require schenke-io/laravel-url-cleaner

Here a code example:

```php
<?php

use SchenkeIo\LaravelUrlCleaner\UrlCleaner;


$shortUrl = (new UrlCleaner)->handle($longUrl);    



```


## Operation principle

The core `UrlCleaner` class iteratively applies a series of specialized 
cleaner classes to a given URL. Each cleaner class performs a specific modification 
to check and clean the URL for the following reasons:

- **Reducing URL clutter:** Removes unnecessary SEO parameters and tracking information.
- **Improving data storage efficiency:** Stores cleaner, more concise URLs.
- **Enhancing performance:** Optimizes URL processing and caching.
- **Securing sensitive information:** Prevents exposure of tracking parameters.
- **Enhancing data analysis:** Simplifies data analysis by removing noise from URLs.


This cleaner classes are highly extensible, allowing for customization and the creation of 
new modification types.


