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
     * @param array|null $scopes
     * @param string|null $driver
     * @return $this
     */
    public function actingAs(\Illuminate\Contracts\Auth\Authenticatable $user, $scopes = null, $driver = null)
    {
        $permissions = is_null($scopes) ? $user->role->permissions->pluck('slug')->toArray() : $scopes;

        /** @var $user \App\Models\User */
        Passport::actingAs(
            $user,
            $permissions
        );

        return $this;
    }
}
