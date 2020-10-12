<?php

namespace Tests;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Passport\Passport;

/**
 * Class TestCase
 * @package Tests
 */
abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase;

    /**
     * Api url.
     *
     * @var string
     */
    const API_URL = 'api/v1/';

    /**
     * An admin user
     *
     * @var User
     */
    protected $admin;

    public function setUp(): void
    {
        parent::setUp();

        // Create admin
        $this->admin = User::factory()
            ->isActive()
            ->isAdmin()
            ->isVerified()
            ->noPasswordChange()
            ->tosAccepted()
            ->create();
    }

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
        $permissions = \is_null($scopes) ? $user->role->permissions->pluck('slug')->toArray() : $scopes;

        /** @var $user User */
        Passport::actingAs(
            $user,
            $permissions
        );

        return $this;
    }

    /**
     * Check if the user hasn't the permissions.
     *
     * @param string $resource
     * @param string $action
     */
    protected function checkForbidden($resource, $action = 'get')
    {
        $user= User::factory()->isActive()->isUser()->isVerified()->noPasswordChange()->tosAccepted()->create();
        $this->actingAs($user);

        $response = $this->getResponse($resource, $action);
        $response->assertStatus(403)->assertJson(['message' => 'Invalid scope(s) provided.']);
    }

    /**
     * Check if the input is invalid.
     *
     * @param string $resource
     * @param string $action
     * @param array $data
     * @param array $errors
     */
    protected function checkInvalidInput($resource, $action = 'post', $data = [], $errors = [])
    {
        $response = $this->getResponse($resource, $action, $data);
        $response->assertStatus(422)
            ->assertJsonStructure(['message', 'errors'])
            ->assertJsonFragment(['message' => 'The given data was invalid.']);

        if (\count($errors) > 0) {
            $response->assertStatus(422)
                ->assertJsonFragment(['errors' => $errors]);
        }
    }

    /**
     * Check if the user is unauthorized
     *
     * @param string $resource
     * @param string $action
     */
    protected function checkUnauthorized($resource, $action = 'get')
    {
        $response = $this->getResponse($resource, $action);
        $response->assertStatus(401)->assertJson(['message' => 'You are not signed in.']);
    }

    /**
     * Return the response depending on the action.
     *
     * @param string $resource
     * @param string $action
     * @param array $data
     * @return \Illuminate\Testing\TestResponse
     */
    private function getResponse($resource, $action, $data = [])
    {
        switch ($action) {
            case 'delete':
                return $this->deleteJson($resource, $data);

            case 'post':
                return $this->postJson($resource, $data);

            case 'put':
                return $this->putJson($resource, $data);

            default:
                return $this->getJson($resource);
        }
    }
}
