<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Passport\Passport;

/**
 * Class TestCase
 * @package Tests
 */
abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Api url.
     *
     * @var string
     */
    const API_URL = 'api/v1/';

    /**
     * Authenticate the user.
     *
     * @param \Illuminate\Contracts\Auth\Authenticatable $user
     * @param string|null $driver
     * @return $this
     */
    public function actingAs(\Illuminate\Contracts\Auth\Authenticatable $user, $driver = null)
    {
        /** @var $user \App\Models\User */
        Passport::actingAs(
            $user,
            $user->role->permissions->pluck('slug')->toArray()
        );

        return $this;
    }
}
