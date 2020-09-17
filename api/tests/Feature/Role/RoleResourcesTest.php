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
        $this->roles = factory(Role::class, 5)->create();
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
        $response = $this->deleteJson($resourceRoles . '999');
        $response->assertStatus(404)->assertJson(['message' => 'The requested resource was not found.']);

        // Protected
        $this->roles->push(factory(Role::class)->state('notDeletable')->create());
        $response = $this->deleteJson($resourceRoles . $this->roles->last()->id);
        $response->assertStatus(400)
            ->assertJson(['message' => 'The user role is protected and cannot be deleted.']);

        // Not deletable - Assign user to group
        $this->roles->push(factory(Role::class)->create());
        $user = factory(User::class)->create();
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
        $this->roles[0] = factory(Role::class)->state('notDeletable')->create();
        $this->roles[1] = factory(Role::class)->create();

        // Assign user to group
        $user = factory(User::class)->create();
        $user->role_id = $this->roles[1]->id;
        $user->save();

        $this->checkInvalidInput($resource, 'delete', ['ids' => [$this->roles[0]->id, $this->roles[1]->id]], [
            'ids.0' => ['The user role with the id ' . $this->roles[0]->id . ' is protected.'],
            'ids.1' => ['At least one user is still assigned to the user role with id ' . $this->roles[1]->id . ', remove all users from this role to proceed.']
        ]);

        // Success
        $this->roles = factory(Role::class, 5)->create();
        $response = $this->deleteJson($resource, ['ids' => $this->roles->pluck('id')->toArray()]);
        $response->assertStatus(200)->assertJson(['message' => '5 users roles have been deleted successfully.']);
        foreach ($this->roles as $role) {
            $this->assertDeleted($role);
        }
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
        $response = $this->getJson($resourceRoles . '999');
        $response->assertStatus(404)->assertJson(['message' => 'The requested resource was not found.']);

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
        $response = $this->getJson($resourceRoles . '999');
        $response->assertStatus(404)->assertJson(['message' => 'The requested resource was not found.']);

        // Not editable
        $notEditable = factory(Role::class)->state('notEditable')->create();
        $response = $this->putJson($resourceRoles . $notEditable->id, ['permissions' => ['users:read']]);
        $response->assertStatus(400)->assertJson(['message' => 'The user role is protected. The permissions of this user role cannot be changed.']);

        // Should set default permissions
        $defaultPermission = factory(Role::class)->create();
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
}
