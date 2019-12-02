<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Cloud Name
    |--------------------------------------------------------------------------
    |
    | This is the name of your Cloudinary cloud name
    | It can commonly be found on the upper left part of the Cloudinary
    | dashboard.
    |
    */

    'cloud_name' => env('CLOUDINARY_CLOUD_NAME', ''),

    /*
    |--------------------------------------------------------------------------
    | API Key
    |--------------------------------------------------------------------------
    |
    | This is your public Cloudinary API key
    | It can commonly be found on the upper left part of the Cloudinary
    | dashboard.
    |
    */

    'api_key' => env('CLOUDINARY_API_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | API Secret
    |--------------------------------------------------------------------------
    |
    | This is your secret Cloudinary key
    | It can commonly be found on the upper left part of the Cloudinary
    | dashboard (remember to click on "Reveal")
    |
    */

    'api_secret' => env('CLOUDINARY_API_SECRET', ''),

    /*
    |--------------------------------------------------------------------------
    | Cloudinary Username
    |--------------------------------------------------------------------------
    |
    | This is the email address of the Cloudinary account you want to use.
    |
    */

    'username' => env('CLOUDINARY_USERNAME', ''),
];
