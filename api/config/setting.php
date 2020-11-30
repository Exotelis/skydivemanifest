<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Setting File
    |--------------------------------------------------------------------------
    |
    | This file stores all settings that were not defined by laravel.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Google services
    |--------------------------------------------------------------------------
    |
    | Google services like keys of reCAPTCHA 3.
    |
    */

    'google' => [
        'recaptcha3' => [
            'private' => env('GOOGLE_RECAPTCHA3_PRIVATE', null),
            'public'  => env('GOOGLE_RECAPTCHA3_PUBLIC', null),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Waiver
    |--------------------------------------------------------------------------
    |
    | Waiver settings:
    |
    | keep: The period (months) for how long a signed waiver is valid if it's
    | assigned to a user. 0 = will not expire.
    | keep_unassigned: The period (months) for how long a signed waiver is
    | valid if it's not assigned to a user. 0 = will not expire.
    |
    */

    'waivers' => [
        'keep'            => (int) env('WAIVERS_KEEP', 12),
        'keep_unassigned' => (int) env('WAIVERS_KEEP_UNASSIGNED', 12),
    ],

];
