<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class MySiteClass
 * @package App\Facades
 *
 * @method static \App\Helpers\VerifyEmail config(string $config)
 * @method static string create(string $email, string $newEmail)
 * @method static bool delete(string $email)
 * @method static bool exists(string $email)
 * @method static int expires(\Illuminate\Database\Eloquent\Model $user = null)
 * @method static mixed findUser(string $token)
 * @method static \Illuminate\Database\Eloquent\Model|object|null get(string $email)
 * @method static bool createdRecently(string $email)
 * @method static int throttle()
 * @method static bool verify(string $token, \Illuminate\Database\Eloquent\Model $user = null)
 *
 * @see \App\Helpers\VerifyEmail
 */
class VerifyEmail extends Facade
{
    /**
     * Return the facade accessor.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'verifyemail';
    }
}
