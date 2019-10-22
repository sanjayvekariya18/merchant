<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */



    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

    'google' => [
        'client_id' => '',
        'client_secret' => '',
        'redirect' => ''
    ],

    'facebook' => [
        'client_id' => '',
        'client_secret' => '',
        'redirect' => ''
    ],

    'twitter' => [
        'client_id' => '',
        'client_secret' => '',
        'redirect' => ''
    ], 

    'linkedin' => [
        'client_id' => '',
        'client_secret' => '',
        'redirect' => ''
    ],

    'github' => [
        'client_id' => '',
        'client_secret' => '',
        'redirect' => ''
    ],

    'meetup' => [
        'client_id' => '',
        'client_secret' => '',
        'redirect' => ''
    ],

    'eventbrite' => [
        'client_id' => '',
        'client_secret' => '',
        'redirect' => ''
    ],

    'flickr' => [
        'client_id' => '',
        'client_secret' => '',
        'redirect' => ''
    ],

    'foursquare' => [
        'client_id' => '',
        'client_secret' => '',
        'redirect' => ''
    ],

    'instagram' => [
        'client_id' => '',
        'client_secret' => '',
        'redirect' => ''
    ],

    'strava' => [
        'client_id' => '',
        'client_secret' => '',
        'redirect' => ''
    ],

    'weibo' => [
        'client_id' => '',
        'client_secret' => '',
        'redirect' => ''
    ], 

    'freelancer' => [
        'client_id' => '',
        'client_secret' => '',
        'redirect' => ''
    ],    
];
