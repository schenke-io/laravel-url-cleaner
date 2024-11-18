<?php

use SchenkeIo\LaravelUrlCleaner\Cleaners;

/*
 |-----------------------------------------------------------------------------
 | Laravel URL cleaner
 |-----------------------------------------------------------------------------
 |
 |
 |
 |
 |
 */

return [
    /*
     * This list of cleaner files are either from the package or your own. They will be processed
     * in the given order.
     */
    'cleaners' => [
        Cleaners\PreventUserPassword::class,
        Cleaners\PreventNonHttps::class,
        Cleaners\ShortAmazonProductUrl::class,
        Cleaners\MarketingBroad::class,

        /*
         * works together with 'masks'
         */
        //        Cleaners\RemoveConfigMasks::class,

        /*
         * works together with 'max_length_value'
         */
        //        Cleaners\RemoveLongValues::class,

        Cleaners\SortParameters::class,
    ],
    /*
     * When you use PreventLongValues::class the value is used to remove keys with long values.
     */
    'max_length_value' => 32,
    /*
     * If you want to add your own masks than use RemoveConfig::class and fill this array.
     */
    'masks' => [],
    /*
     * all they listed here are never removed
     */
    'protected_keys' => [],
];
