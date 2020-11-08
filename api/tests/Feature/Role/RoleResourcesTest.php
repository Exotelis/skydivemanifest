<?php

namespace Tests\Feature\Role;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * Class RoleResourcesTest
 * @package Tests\Feature\Role
 */
class RoleResourcesTest extends TestCase
{
    use WithFaker;

    protected $roles;

    public function setUp(): void
    {
        parent::setUp();

        // Create roles
        $this->roles = Role::factory()->count(5)->create();
    }

    /**
     * Test [GET] roles resource.
     *
     * @return void
     */
    public function testAll()
    {
        $resource = self::API_URL . 'roles';

        // Unauthorized
        $this->checkUnauthorized($resource);

        // Forbidden
        $this->checkForbidden($resource);

        // Sign in as admin
        $this->actingAs($this->admin, ['roles:read']);

        // Invalid filtering
        $response = $this->getJson($resource . '?filter[invalid]=somevalue');
        $response->assertStatus(400)
            ->assertJsonFragment(['message' => 'Requested filter(s) `invalid` are not allowed. Allowed filter(s) are `deletable, editable, id, name`.']);

        // Invalid sorting
        $response = $this->getJson($resource . '?sort=invalid');
        $response->assertStatus(400)
            ->assertJsonFragment(['message' => 'Requested sort(s) `invalid` is not allowed. Allowed sort(s) are `deletable, editable, id, name`.']);

        // Success
        $response = $this->getJson($resource);
        $response
            ->assertStatus(200)
            ->assertJsonFragment(['per_page' => 50])
            ->assertJsonFragment(['total' => Role::all()->count()]);

        // Filtering
        $response = $this->getJson($resource . '?filter[name]=' . $this->roles[0]->name);
        $count = Role::where('name', 'like', '%' .  $this->roles[0]->name . '%')->count();
        $response
            ->assertStatus(200)
            ->assertJsonFragment(['total' => $count]);
    }

    /**
     * Test [POST] roles resource.
     *
     * @return void
     */
    public function testCreate()
    {
        $resource = self::API_URL . 'roles';
        $json = [
            'color'       => $this->faker->hexColor,
            'deletable'   => true,
            'editable'    => true,
            'name'        => $this->faker->unique()->word,
            'permissions' => ['personal', 'users:write'],
        ];

        // Unauthorized
        $this->checkUnauthorized($resource, 'post');

        // Forbidden
        $this->checkForbidden($resource, 'post');

        // Sign in as admin
        $this->actingAs($this->admin, ['roles:read','roles:write']);

        // Invalid input
        $this->checkInvalidInput($resource, 'post', ['color' => '#ghijkl', 'name' => 'TestRole']);

        // Success
        $response = $this->postJson($resource, $json);
        unset($json['permissions']); // Remove from JSON to compare with database
        $this->assertDatabaseHas('roles', $json);
        $response->assertStatus(201)->assertJson(['message' => 'The user role has been created successfully.']);
    }

    /**
     * Test [DELETE] roles/:id resource.
     *
     * @return void
     */
    public function testDelete()
    {
        $resource = self::API_URL . 'roles/' . $this->roles->first()->id;
        $resourceRoles = self::API_URL . 'roles/';

        // Unauthorized
        $this->checkUnauthorized($resource, 'delete');

        // Forbidden
        $this->checkForbidden($resource, 'delete');

        // Sign in as admin
        $this->actingAs($this->admin, ['roles:read','roles:delete']);

        // Not found
        $this->checkNotFound($resourceRoles . '999', 'delete');

        // Protected
        $this->roles->push(Role::factory()->notDeletable()->create());
        $response = $this->deleteJson($resourceRoles . $this->roles->last()->id);
        $response->assertStatus(400)
            ->assertJson(['message' => 'The user role is protected and cannot be deleted.']);

        // Not deletable - Assign user to group
        $this->roles->push(Role::factory()->create());
        $user = User::factory()->create();
        $user->role_id = $this->roles->last()->id;
        $user->save();
        $response = $this->deleteJson($resourceRoles . $this->roles->last()->id);
        $response->assertStatus(400)->assertJson(['message' => 'At least one user is still assigned to this user role, remove all users from this role to proceed.']);

        // Success
        $response = $this->deleteJson($resource);
        $response->assertStatus(200)->assertJson(['message' => 'The user role has been deleted successfully.']);
        $this->assertDeleted($this->roles->first());
    }

    /**
     * Test [DELETE] roles resource.
     *
     * @return void
     */
    public function testDeleteBulk()
    {
        $resource = self::API_URL . 'roles';

        // Unauthorized
        $this->checkUnauthorized($resource, 'delete');

        // Forbidden
        $this->checkForbidden($resource, 'delete');

        // Sign in as admin
        $this->actingAs($this->admin, ['roles:read','roles:delete']);

        // Invalid input
        $this->roles[0] = Role::factory()->notDeletable()->create();
        $this->roles[1] = Role::factory()->create();

        // Assign user to group
        $user = User::factory()->create();
        $user->role_id = $this->roles[1]->id;
        $user->save();

        $this->checkInvalidInput($resource, 'delete', ['ids' => [$this->roles[0]->id, $this->roles[1]->id]], [
            'ids.0' => ['The user role with the id ' . $this->roles[0]->id . ' is protected.'],
            'ids.1' => ['At least one user is still assigned to the user role with id ' . $this->roles[1]->id . ', remove all users from this role to proceed.']
        ]);

        // Success
        $this->roles = Role::factory()->count(5)->create();
        $response = $this->deleteJson($resource, ['ids' => $this->roles->pluck('id')->toArray()]);
        $response->assertStatus(200)->assertJson(['message' => '5 users roles have been deleted successfully.']);
        foreach ($this->roles as $role) {
            $this->assertDeleted($role);
        }
    }

    /**
     * Test [GET] roles/names resource.
     *
     * @return void
     */
    public function testNames()
    {
        $resource = self::API_URL . 'roles/names';

        // Unauthorized
        $this->checkUnauthorized($resource);

        // Forbidden
        $this->actingAs($this->admin, ['invalid']);
        $response = $this->getJson($resource);
        $response->assertStatus(403)->assertJson(['message' => 'Invalid scope(s) provided.']);

        // Sign in as admin
        $this->actingAs($this->admin);

        // Success
        $response = $this->getJson($resource);
        $response->assertStatus(200)->assertJson(Role::all()->pluck('name')->toArray());
    }

    /**
     * Test [GET] roles/:id resource.
     *
     * @return void
     */
    public function testRole()
    {
        $resource = self::API_URL . 'roles/' . $this->roles->first()->id;
        $resourceRoles = self::API_URL . 'roles/';

        // Unauthorized
        $this->checkUnauthorized($resource);

        // Forbidden
        $this->checkForbidden($resource);

        // Sign in as admin
        $this->actingAs($this->admin, ['roles:read']);

        // Not found
        $this->checkNotFound($resourceRoles . '999');

        // Success
        $response = $this->getJson($resource);
        $response->assertStatus(200)->assertJson($this->roles->first()->toArray());
    }

    /**
     * Test [PUT] roles/:id resource.
     *
     * @return void
     */
    public function testUpdate()
    {
        $resource = self::API_URL . 'roles/' . $this->roles->first()->id;
        $resourceRoles = self::API_URL . 'roles/';

        // Unauthorized
        $this->checkUnauthorized($resource, 'put');

        // Forbidden
        $this->checkForbidden($resource, 'put');

        // Sign in as admin
        $this->actingAs($this->admin, ['roles:read', 'roles:write']);

        // Not found
        $this->checkNotFound($resourceRoles . '999', 'put');

        // Not editable
        $notEditable = Role::factory()->notEditable()->create();
        $response = $this->putJson($resourceRoles . $notEditable->id, ['permissions' => ['users:read']]);
        $response->assertStatus(400)->assertJson(['message' => 'The user role is protected. The permissions of this user role cannot be changed.']);

        // Should set default permissions
        $defaultPermission = Role::factory()->create();
        $response = $this->putJson($resourceRoles . $defaultPermission->id, ['permissions' => ['users:read']]);
        $response->assertStatus(200)
            ->assertJsonFragment(['permission_slug' => 'personal']);

        // Invalid input
        $this->checkInvalidInput($resource, 'put', ['name' => $this->roles->last()->name], [
            'name' => ['The name has already been taken.']
        ]);

        // Success
        $newColor = $this->faker->hexColor;
        $response = $this->putJson($resource, ['color' => $newColor]);
        $response->assertStatus(200)
            ->assertJsonFragment(['color' => $newColor]);
        $this->assertDatabaseHas('roles', ['color' => $newColor]);
    }

    /**
     * Test [GET] roles/valid resource.
     *
     * @return void
     */
    public function testValid()
    {
        $resource = self::API_URL . 'roles/valid';

        // Unauthorized
        $this->checkUnauthorized($resource);

        // Forbidden
        $this->actingAs($this->admin, ['invalid']);
        $response = $this->getJson($resource);
        $response->assertStatus(403)->assertJson(['message' => 'Invalid scope(s) provided.']);

        // Sign in as admin
        $this->actingAs($this->admin);

        // Success
        $response = $this->getJson($resource);
        $response->assertStatus(200)->assertJson(validRoles(auth()->user())->toArray());
    }
}
