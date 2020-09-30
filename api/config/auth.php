<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | This option controls the default authentication "guard" and password
    | reset options for your application. You may change these defaults
    | as required, but they're a perfect start for most applications.
    |
    */

    'defaults' => [
        'guard'     => 'api',
        'passwords' => 'users',
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Next, you may define every authentication guard for your application.
    | Of course, a great default configuration has been defined for you
    | here which uses session storage and the Eloquent user provider.
    |
    | All authentication drivers have a user provider. This defines how the
    | users are actually retrieved out of your database or other storage
    | mechanisms used by this application to persist your user's data.
    |
    | Supported: "session", "token"
    |
    */

    'guards' => [
        'api' => [
            'driver'   => 'passport',
            'provider' => 'users',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    | All authentication drivers have a user provider. This defines how the
    | users are actually retrieved out of your database or other storage
    | mechanisms used by this application to persist your user's data.
    |
    | If you have multiple user tables or models you may configure multiple
    | sources which represent each model / table. These sources may then
    | be assigned to any extra authentication guards you have defined.
    |
    | Supported: "database", "eloquent"
    |
    */

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model'  => App\Models\User::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    |
    | You may specify multiple password reset configurations if you have more
    | than one user table or model in the application and you want to have
    | separate password reset settings based on the specific user types.
    |
    | The expire time is the number of minutes that the reset token should be
    | considered valid. This security feature keeps tokens short-lived so
    | they have less time to be guessed. You may change this as needed.
    |
    | The throttle time is the number of seconds how long a user have to
    | wait before resetting the password again.
    |
    */

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table'    => 'password_resets',
            'expire'   => 120,
            'throttle' => 600,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Email changes
    |--------------------------------------------------------------------------
    |
    | You may specify multiple email change configurations if you have more
    | than one user table or model in the application and you want to have
    | separate email change settings based on the specific user types.
    |
    | The expire time is the number of minutes that the change token should be
    | considered valid. This security feature keeps tokens short-lived so
    | they have less time to be guessed. You may change this as needed.
    | Default 1 day.
    |
    | The throttle time is the number of seconds how long a user have to
    | wait before requesting or resending the email address change request
    | again. Default 5 minutes.
    |
    */

    'email_changes' => [
        'users' => [
            'provider' => 'users',
            'table'    => 'email_changes',
            'expire'   => 1440,
            'throttle' => 300,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    |
    | Here you may define the amount of seconds before a password confirmation
    | times out and the user is prompted to re-enter their password via the
    | confirmation screen. By default, the timeout lasts for three hours.
    |
    */

    'password_timeout' => 10800,

    /*
    |--------------------------------------------------------------------------
    | Failed logins
    |--------------------------------------------------------------------------
    |
    | Those keys are used to define after how many failed login attempts the
    | user account should be locked and how long the account should be locked.
    | After x tries, for x minutes.
    |
    */

    'lock' => [
        'after_tries' => 3,
        'for_minutes' => 30,
    ],

    /*
    |--------------------------------------------------------------------------
    | Password strength
    |--------------------------------------------------------------------------
    |
    | This key defines the required strength of passwords used in you project.
    | It is possible to choose out of three existing levels.
    | regexWeakPassword, regexMediumPassword or regexStrongPassword
    |
    */

    'password_strength' => 'regexMediumPassword',

];
