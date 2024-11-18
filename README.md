
# Laravel URL cleaner - clean and concise

[![Latest Version on Packagist](https://img.shields.io/packagist/v/schenke-io/laravel-url-cleaner.svg?style=flat-square)](https://packagist.org/packages/schenke-io/laravel-url-cleaner)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/schenke-io/laravel-url-cleaner/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/schenke-io/laravel-relation-manager/actions?query=workflow%3Arun-tests+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/schenke-io/laravel-url-cleaner.svg?style=flat-square)](https://packagist.org/packages/schenke-io/laravel-url-cleaner)
![](/.github/coverage-badge.svg)

![](/.github/clean-url.png)

The Laravel URL Cleaner package sanitizes URLs by removing 
unnecessary SEO parameters, tracking information, and other 
clutter, ensuring clean and efficient URL handling in 
your Laravel applications.

To install just run:

      composer require schenke-io/clean-url

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
##  List of cleaner classes

<table>
<tr style="background-color: silver;">
<th>class name</th>
<th># masks</th>
<th>description</th>
</tr>
<tr><td>Marketing00</td><td>68</td><td>Manual collected list of parameters for cleaning</td></tr>
<tr><td>Marketing01</td><td>94</td><td>tracking-query-params-registry from https://github.com/mpchadwick</td></tr>
<tr><td>Marketing02</td><td>43</td><td>url-parameter-tracker-list from https://github.com/spekulatius</td></tr>
<tr><td>Marketing03</td><td>170</td><td>Neat-URL from https://github.com/Smile4ever</td></tr>
<tr><td>Marketing04</td><td>91</td><td>platform-url-click-id-parameters from https://github.com/henkisdabro</td></tr>
<tr><td>MarketingBroad</td><td>226</td><td>prioritize generic masks from all sources</td></tr>
<tr><td>MarketingNarrow</td><td>309</td><td>prioritize specific masks from all sources</td></tr>
<tr><td>MarketingUnique</td><td>348</td><td>all masks from all sources</td></tr>
<tr><td>PreventInvalidHost</td><td>-</td><td>do not allow urls with invalid host names</td></tr>
<tr><td>PreventLocalhost</td><td>-</td><td>do not allow urls from localhost</td></tr>
<tr><td>PreventNonHttps</td><td>-</td><td>do not allow urls different from the scheme https</td></tr>
<tr><td>PreventUserPassword</td><td>-</td><td>do not allow urls using user and passwords</td></tr>
<tr><td>RemoveConfigMasks</td><td>-</td><td>remove keys defined in the config</td></tr>
<tr><td>RemoveLongValues</td><td>-</td><td>remove overly long parameters.</td></tr>
<tr><td>RemoveSearch</td><td>-</td><td>remove typical search parameters</td></tr>
<tr><td>ShortAmazonProductUrl</td><td>-</td><td>Amazon product url cleaner</td></tr>
<tr><td>SortParameters</td><td>-</td><td>the query parameters get alphabetical sorted</td></tr>
</table>


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


## The use of masks

The core process of URL parameter removal utilizes specific masks.

| Description                                  | Example mask                                             |
|----------------------------------------------|----------------------------------------------------------|
| exact match of one query key on any domain   | utm_campaign                                             |
| match of some keys on any domain             | utm_&#42;<br> &#42;tm_&#42;                              |
| exact match of one query key on one domain   | utm_campaign@test.net                                    |
| exact match of one query key on some domains | utm_campaign@test.&#42; <br>utm_campaign@&#42;test.&#42; |
| match of some keys on one domain             | utm_&#42;@test.net <br>&#42;x*@test.net                  |
| match of some keys on some domains           | utm_&#42;@test.&#42;   <br>&#42;x*@&#42;test.&#42;       |



Soem examples are outlined in the table below.

| Mask       | URL 1<br> test.com/?a=1&b=2 | URL 2<br> test.net/?a=1&abb=2 | URL 3<br>  test2.com/?a=1&b=2 |
|------------|------------------------------|-------------------------------|-------------------------------|
| a          | test.com/?b=2                | test.net/?abb=2               | test2.com/?b=2                |                           |
| a*         | test.com/?b=2                | test.net/                     | test2.com/?b=2                |
| test.com@a | test.com/?b=2                | test.net/?a=1&abb=2           | test2.com/?a=1&b=2            |
| test.*@a   | test.com/?b=2                | test.net/?abb=2               | test2.com/?a=1&b=2            |

## Build your own cleaner by extending special classes 

To extend the list of cleaners you can build your own 
cleaners and put them in the config 
file `config/url-cleaner.php`

The following cleaners are prepared to be extended 
for custom applications:




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

## Data sources

Currently, the following sources are used:
- https://docs.flyingpress.com/en/article/ignore-query-parameters-yfejfj/
- https://support.cloudways.com/en/articles/8437462-how-to-enable-ignore-query-string-for-varnish-cache
- https://github.com/mpchadwick/tracking-query-params-registry
- https://github.com/spekulatius/url-parameter-tracker-list
- https://github.com/Smile4ever/Neat-URL
- https://github.com/henkisdabro/platform-url-click-id-parameters
- https://data.iana.org/TLD/tlds-alpha-by-domain.txt
