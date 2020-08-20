<?php

namespace Tests\Feature\Permission;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Class PermissionResourcesTest
 * @package Tests\Feature\Permission
 */
class PermissionResourcesTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    public function setUp(): void
    {
        parent::setUp();

        // Create admin
        $this->admin = factory(User::class)
            ->states('isActive', 'isVerified', 'noPasswordChange')
            ->create();
    }

    /**
     * Test [GET] permissions resource.
     *
     * @return void
     */
    public function testAll()
    {
        $resource = self::API_URL . 'permissions';

        // Unauthorized
        $response = $this->getJson($resource);
        $response->assertStatus(401)->assertJson(['message' => 'You are not signed in.']);

        // Forbidden
        $user = factory(User::class)
            ->states( 'isActive', 'isVerified', 'noPasswordChange', 'noPermissions')
            ->create();
        $this->actingAs($user);
        $response = $this->getJson($resource);
        $response->assertStatus(403)->assertJson(['message' => 'Invalid scope(s) provided.']);

        // Sign in as admin
        $this->actingAs($this->admin, ['permissions:read']);

        // Success
        $response = $this->getJson($resource);
        $response->assertStatus(200)->assertJson(Permission::all()->toArray());
    }
}
