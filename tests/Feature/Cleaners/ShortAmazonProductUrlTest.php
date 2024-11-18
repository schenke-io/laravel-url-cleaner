<?php

use SchenkeIo\LaravelUrlCleaner\Cleaners\ShortAmazonProductUrl;
use SchenkeIo\LaravelUrlCleaner\Data\UrlData;

it('can shorten Amazon product urls', function ($asin, $url) {

    $urlData = new UrlData($url);
    $urlOut = $urlData->fullHost()."/dp/$asin";

    (new ShortAmazonProductUrl)->clean($urlData);
    expect($urlData->getUrl())->toBe($urlOut);
})->with([
    'url01' => ['B0015T963C', 'http://www.amazon.com/Kindle-Wireless-Reading-Display-Generation/dp/B0015T963C'],
    'url02' => ['B0015T963C', 'http://www.amazon.com/dp/B0015T963C'],
    'url03' => ['B0015T963C', 'http://www.amazon.com/gp/product/B0015T963C'],
    'url04' => ['B0015T963C', 'http://www.amazon.com/gp/product/glance/B0015T963C'],
    'url05' => ['B00LGAQ7NW', 'https://www.amazon.de/gp/product/B00LGAQ7NW/ref=s9u_simh_gw_i1?ie=UTF8&pd_rd_i=B00LGAQ7NW&pd_rd_r=5GP2JGPPBAXXP8935Q61&pd_rd_w=gzhaa&pd_rd_wg=HBg7f&pf_rd_m=A3JWKAKR8XB7XF&pf_rd_s=&pf_rd_r=GA7GB6X6K6WMJC6WQ9RB&pf_rd_t=36701&pf_rd_p=c210947d-c955-4398-98aa-d1dc27e614f1&pf_rd_i=desktop'],
    'url06' => ['B00FA2RLX2', 'https://www.amazon.de/Sawyer-Wasserfilter-Wasseraufbereitung-Outdoor-Filter/dp/B00FA2RLX2/ref=pd_sim_200_3?_encoding=UTF8&psc=1&refRID=NMR7SMXJAKC4B3MH0HTN'],
    'url07' => ['B01DFJTYSQ', 'https://www.amazon.de/Notverpflegung-Kg-Marine-wasserdicht-verpackt/dp/B01DFJTYSQ/ref=pd_sim_200_5?_encoding=UTF8&psc=1&refRID=7QM8MPC16XYBAZMJNMA4'],
    'url08' => ['B01N32MQOA', 'https://www.amazon.de/dp/B01N32MQOA?psc=1'],
    'url09' => ['B0CMXNWTP5', 'https://www.amazon.co.jp/-/en/Amazon-Basic-Indoor-Single-Outlet/dp/B0CMXNWTP5?ref_=ast_sto_dp&th=1'],
    'url10' => ['B0C9ZJHQHM', 'https://www.amazon.com/-/de/dp/B0C9ZJHQHM/ref=sr_1_2?_encoding=UTF8&content-id=amzn1.sym.dcb5f66d-01e4-4b3e-b87f-bed93b0b8819&dib=eyJ2IjoiMSJ9.EIQyqcOyEhJ2sqpFIADE88N0UZ1c3-mf6yjQUkxSV5UxkbC_NwfL3ciqrODHFKMRpOP5fkfiRb5BHYa26k06VEFtmqLSFaCB-C3Lkss29i-mEw45zVOlFqhW3QvTANy2Hl7mLHBL9PyDU3DI5YCwQLmW9gTIlSi1MvEwkuuBpDYnu4v1Up5lii0iLxNWI2EhzodCxUpWQ9vaD0pzDrCEXb7MlG6QBYb8Svug_atqY3U.mE8q6Jm3PLV4oJNL7J0M3QRGfUWj2ZItKo1JSpZPtM4&dib_tag=se&keywords=gaming%2Bkeyboard&pd_rd_r=a86ef7517e&pd_rd_w=Hyi7K&pd_rd_wg=D5ec7&pf_rd_p=dcb819&pf_rd_r=W540KK11&sr=8-2&th=1'],
]);
