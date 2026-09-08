<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Store URL
    |--------------------------------------------------------------------------
    |
    | Base URL of the WooCommerce store, without the `/wp-json` suffix.
    | Example: https://example.com
    |
    */
    'base_url' => env('WOOCOMMERCE_BASE_URL'),

    /*
    |--------------------------------------------------------------------------
    | API Keys
    |--------------------------------------------------------------------------
    |
    | Consumer key/secret generated at WooCommerce > Settings > Advanced >
    | REST API. They are sent as HTTP Basic credentials, which WooCommerce
    | only accepts over HTTPS.
    |
    */
    'consumer_key' => env('WOOCOMMERCE_CONSUMER_KEY'),
    'consumer_secret' => env('WOOCOMMERCE_CONSUMER_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | REST API Namespace
    |--------------------------------------------------------------------------
    |
    | Route namespace appended to the store URL. Change it to target another
    | WooCommerce API version (e.g. `wp-json/wc/v2`).
    |
    */
    'namespace' => env('WOOCOMMERCE_NAMESPACE', 'wp-json/wc/v3'),

];
