<?php

namespace Tests\Feature\User;

use App\Models\Address;
use App\Models\Country;
use App\Models\Region;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserResourcesTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $users;

    public function setUp(): void
    {
        parent::setUp();

        // Create admin
        $this->admin = factory(User::class)
            ->states('isActive', 'isVerified', 'noPasswordChange')
            ->create();

        // Create countries and regions
        factory(Country::class, 3)->create()->each(function ($country) {
            factory(Region::class, 2)->create(['country_id' => $country->id]);
        });

        // Create users
        $this->users = factory(User::class, 8)->states('noPermissions')->create()
            ->each(function ($user) {
            // Generate 0 to 4 addresses per user
            $addresses = factory(Address::class, rand(0,4))->create(['user_id' => $user->id]);
            $addresses = $addresses->toArray();

            // Select default invoice and default shipping address
            if (count($addresses) > 0) {
                $user->default_invoice = $addresses[array_rand($addresses)]['id'];
                $user->default_shipping = $addresses[array_rand($addresses)]['id'];
                $user->save();
            }
        });
    }

    /**
     * Test [GET] users resource.
     *
     * @return void
     */
    public function testAll()
    {
        $resource = self::API_URL . 'users';

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
        $this->actingAs($this->admin, ['users:read']);

        // Invalid filtering
        $response = $this->getJson($resource . '?filter[invalid]=somevalue');
        $response->assertStatus(400)
            ->assertJsonFragment(['message' => 'Requested filter(s) `invalid` are not allowed. Allowed filter(s) are `age, age_eot, age_eyt, city, country, default_invoice_city, default_invoice_country, default_invoice_postal, default_invoice_region, default_invoice_street, default_shipping_city, default_shipping_country, default_shipping_postal, default_shipping_region, default_shipping_street, dob, dob_at_after, dob_at_before, email, email_verified, firstname, gender, id, is_active, lastname, middlename, mobile, name, phone, postal, region, role, street, username`.']);

        // Invalid sorting
        $response = $this->getJson($resource . '?sort=invalid');
        $response->assertStatus(400)
            ->assertJsonFragment(['message' => 'Requested sort(s) `invalid` is not allowed. Allowed sort(s) are `dob, email, firstname, gender, id, lastname, locale, middlename, mobile, phone, timezone, username, is_active, role`.']);

        // Success
        $response = $this->getJson($resource);
        $response
            ->assertStatus(200)
            ->assertJsonFragment(['per_page' => 50])
            ->assertJsonFragment(['total' => User::all()->count()]);

        // Filtering
        $response = $this->getJson($resource . '?filter[lastname]=' . $this->admin->lastname);
        $count = User::where('lastname', 'like', '%' . $this->admin->lastname . '%')->count();
        $response
            ->assertStatus(200)
            ->assertJsonFragment(['total' => $count]);
    }

    /**
     * Test [DELETE] users resource.
     *
     * @return void
     */
    public function testBulkDelete()
    {
        $resource = self::API_URL . 'users';

        // Unauthorized
        $response = $this->deleteJson($resource, ['ids' => [1]]);
        $response->assertStatus(401)->assertJson(['message' => 'You are not signed in.']);

        // Forbidden
        $user = factory(User::class)
            ->states( 'isActive', 'isVerified', 'noPasswordChange', 'noPermissions')
            ->create();
        $this->actingAs($user);
        $response = $this->deleteJson($resource);
        $response->assertStatus(403)->assertJson(['message' => 'Invalid scope(s) provided.']);

        // Sign in as admin
        $this->actingAs($this->admin, ['users:read', 'users:delete']);

        // Invalid id
        $response = $this->deleteJson($resource, ['ids' => [$this->admin->id]]);
        $response->assertStatus(422)
            ->assertJsonStructure(['message', 'errors'])
            ->assertJsonFragment(['message' => 'The given data was invalid.']);

        // Success
        $ids = array_map(function ($user) { return $user['id']; }, $this->users->toArray());
        $response = $this->deleteJson($resource, ['ids' => $ids]);
        $response
            ->assertStatus(200)
            ->assertJsonFragment(['count' => count($ids)])
            ->assertJsonFragment(['message' => '8 users have been deleted successfully.']);
        $this->users->each(function($user) {
            $this->assertSoftDeleted($user);
        });
    }

    /**
     * Test [DELETE] users/trashed resource.
     *
     * @return void
     */
    public function testDeletePermanently()
    {
        $resource = self::API_URL . 'users/trashed';
        $usersResource = self::API_URL . 'users';

        // Unauthorized
        $response = $this->deleteJson($resource, ['ids' => [1]]);
        $response->assertStatus(401)->assertJson(['message' => 'You are not signed in.']);

        // Forbidden
        $user = factory(User::class)
            ->states( 'isActive', 'isVerified', 'noPasswordChange', 'noPermissions')
            ->create();
        $this->actingAs($user);
        $response = $this->deleteJson($resource);
        $response->assertStatus(403)->assertJson(['message' => 'Invalid scope(s) provided.']);

        // Sign in as admin
        $this->actingAs($this->admin, ['users:read', 'users:delete']);

        // Invalid id
        $response = $this->deleteJson($resource, ['ids' => [$this->admin->id]]);
        $response->assertStatus(422)
            ->assertJsonStructure(['message', 'errors'])
            ->assertJsonFragment(['message' => 'The given data was invalid.']);

        // Soft delete users first
        $ids = array_map(function ($user) { return $user['id']; }, $this->users->toArray());

        $response = $this->deleteJson($resource, ['ids' => $ids]);
        $response
            ->assertStatus(200)
            ->assertJsonFragment(['count' => 0])
            ->assertJsonFragment(['message' => 'No user has been deleted permanently.']);

        $response = $this->deleteJson($usersResource, ['ids' => $ids]);
        $response
            ->assertStatus(200)
            ->assertJsonFragment(['count' => 8])
            ->assertJsonFragment(['message' => '8 users have been deleted successfully.']);

        $this->users->each(function($user) {
            $this->assertSoftDeleted($user);
        });

        // Success
        $response = $this->deleteJson($resource, ['ids' => $ids]);
        $response
            ->assertStatus(200)
            ->assertJsonFragment(['count' => 8])
            ->assertJsonFragment(['message' => '8 users have been deleted permanently.']);

        $this->users->each(function($user) {
            $this->assertDeleted($user);
        });
    }

    /**
     * Test [PUT] users/trashed resource.
     *
     * @return void
     */
    public function testRestore()
    {
        $resource = self::API_URL . 'users/trashed';
        $usersResource = self::API_URL . 'users';

        // Unauthorized
        $response = $this->putJson($resource, ['ids' => [1]]);
        $response->assertStatus(401)->assertJson(['message' => 'You are not signed in.']);

        // Forbidden
        $user = factory(User::class)
            ->states( 'isActive', 'isVerified', 'noPasswordChange', 'noPermissions')
            ->create();
        $this->actingAs($user);
        $response = $this->putJson($resource);
        $response->assertStatus(403)->assertJson(['message' => 'Invalid scope(s) provided.']);

        // Sign in as admin
        $this->actingAs($this->admin, ['users:read', 'users:delete']);

        // Invalid id
        $response = $this->putJson($resource, ['ids' => ['invalidid']]);
        $response->assertStatus(422)
            ->assertJsonStructure(['message', 'errors'])
            ->assertJsonFragment(['message' => 'The given data was invalid.']);

        // Soft delete users first
        $ids = array_map(function ($user) { return $user['id']; }, $this->users->toArray());

        $response = $this->deleteJson($usersResource, ['ids' => $ids]);
        $response
            ->assertStatus(200)
            ->assertJsonFragment(['count' => 8])
            ->assertJsonFragment(['message' => '8 users have been deleted successfully.']);

        $this->users->each(function($user) {
            $this->assertSoftDeleted($user);
        });

        // Success
        $response = $this->putJson($resource, ['ids' => $ids]);
        $response
            ->assertStatus(200)
            ->assertJsonFragment(['count' => 8])
            ->assertJsonFragment(['message' => '8 users have been restored.']);
        $this->users->each(function($user) {
            $this->assertDatabaseHas('users', ['id' => $user->id, 'deleted_at' => null]);
        });
    }

    /**
     * Test [GET] users/trashed resource.
     *
     * @return void
     */
    public function testTrashed()
    {
        $resource = self::API_URL . 'users/trashed';
        $usersResource = self::API_URL . 'users';

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
        $this->actingAs($this->admin, ['users:read', 'users:delete']);

        // Invalid filtering
        $response = $this->getJson($resource . '?filter[invalid]=somevalue');
        $response->assertStatus(400)
            ->assertJsonFragment(['message' => 'Requested filter(s) `invalid` are not allowed. Allowed filter(s) are `age, age_eot, age_eyt, city, country, default_invoice_city, default_invoice_country, default_invoice_postal, default_invoice_region, default_invoice_street, default_shipping_city, default_shipping_country, default_shipping_postal, default_shipping_region, default_shipping_street, dob, dob_at_after, dob_at_before, email, email_verified, firstname, gender, id, is_active, lastname, middlename, mobile, name, phone, postal, region, role, street, username`.']);

        // Invalid sorting
        $response = $this->getJson($resource . '?sort=invalid');
        $response->assertStatus(400)
            ->assertJsonFragment(['message' => 'Requested sort(s) `invalid` is not allowed. Allowed sort(s) are `dob, email, firstname, gender, id, lastname, locale, middlename, mobile, phone, timezone, username, is_active, role`.']);

        // Soft delete users first
        $ids = array_map(function ($user) { return $user['id']; }, $this->users->toArray());

        $response = $this->deleteJson($usersResource, ['ids' => $ids]);
        $response
            ->assertStatus(200)
            ->assertJsonFragment(['count' => 8])
            ->assertJsonFragment(['message' => '8 users have been deleted successfully.']);

        $this->users->each(function($user) {
            $this->assertSoftDeleted($user);
        });

        // Success
        $response = $this->getJson($resource);
        $response
            ->assertStatus(200)
            ->assertJsonFragment(['per_page' => 50])
            ->assertJsonFragment(['total' => count($ids)]);

        // Filtering
        $response = $this->getJson($resource . '?filter[lastname]=' . $this->admin->lastname);
        $count = User::onlyTrashed()->where('lastname', 'like', '%' . $this->admin->lastname . '%')->count();
        $response
            ->assertStatus(200)
            ->assertJsonFragment(['total' => $count]);
    }
}
