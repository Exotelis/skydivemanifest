<?php

namespace Tests\Feature\User;

use App\Models\Address;
use App\Models\Country;
use App\Models\Qualification;
use App\Models\Region;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

/**
 * Class UserResourcesTest
 * @package Tests\Feature\User
 */
class UserResourcesTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * @var \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|User[]
     */
    protected $users;

    public function setUp(): void
    {
        parent::setUp();

        // Create countries and regions
        Country::factory()->count(3)->create()->each(function ($country) {
            Region::factory()->count(2)->create(['country_id' => $country->id]);
        });

        // Create users
        $this->users = User::factory()->count(8)->isUser()->create()->each(function ($user) {
            // Generate 0 to 4 addresses per user
            $addresses = Address::factory()->count(rand(0, 4))->create(['user_id' => $user->id]);
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
     * @covers \App\Http\Controllers\UserController
     * @return void
     */
    public function testAll()
    {
        $resource = self::API_URL . 'users';

        // Unauthorized
        $response = $this->getJson($resource);
        $response->assertStatus(401)->assertJson(['message' => 'You are not signed in.']);

        // Forbidden
        $this->checkForbidden($resource);

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
     * Test [POST] users resource.
     *
     * @covers \App\Http\Controllers\UserController
     * @return void
     */
    public function testCreate()
    {
        $resource = self::API_URL . 'users';
        $json = [
            'dob'             => '1970-01-01',
            'email'           => 'exotelis@mailbox.org',
            'firstname'       => 'John',
            'gender'          => 'm',
            'lastname'        => 'Doe',
            'locale'          => 'en',
            'middlename'      => null,
            'mobile'          => '0049 176 12 34 56 789',
            'password_change' => true,
            'phone'           => '0049 661 12345',
            'role'            => 2,
            'username'        => 'Exotelis',
            'timezone'        => 'Europe/Berlin',
        ];

        Notification::fake();

        // Unauthorized
        $this->checkUnauthorized($resource, 'post');

        // Forbidden
        $this->checkForbidden($resource, 'post');

        // Sign in as admin
        $this->actingAs($this->admin);

        // Invalid input
        $this->checkInvalidInput($resource, 'post', ['email' => 'exotelis@mailbox.org']);

        // Success
        $response = $this->postJson($resource, $json);
        $user = User::find($response['data']['id']);
        $response->assertStatus(201)->assertJson(['message' => 'The user has been created successfully.']);

        // Check database
        $json['role_id'] = $json['role'];
        unset($json['role']);
        $this->assertDatabaseHas('users', $json);

        // Check Notification
        Notification::assertSentTo(
            [$user],
            \App\Notifications\CreateUser::class
        );
    }

    /**
     * Test [DELETE] users resource.
     *
     * @covers \App\Http\Controllers\UserController
     * @return void
     */
    public function testBulkDelete()
    {
        $resource = self::API_URL . 'users';

        Notification::fake();

        // Unauthorized
        $response = $this->deleteJson($resource, ['ids' => [1]]);
        $response->assertStatus(401)->assertJson(['message' => 'You are not signed in.']);

        // Forbidden
        $this->checkForbidden($resource);

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
            Notification::assertSentTo(
                [$user],
                \App\Notifications\SoftDeleteUser::class
            );
        });
    }

    /**
     * Test [DELETE] users/{id} resource.
     *
     * @covers \App\Http\Controllers\UserController
     * @return void
     */
    public function testDelete()
    {
        $resource = self::API_URL . 'users/' . $this->users->first()->id;
        $resourceUsers = self::API_URL . 'users/';

        Notification::fake();

        // Unauthorized
        $this->checkUnauthorized($resource, 'delete');

        // Forbidden
        $this->checkForbidden($resource, 'delete');

        // Sign in as admin
        $this->actingAs($this->admin);

        // Not found
        $this->checkNotFound($resourceUsers . '999', 'delete');

        // Can be deleted because it's the last admin
        $response = $this->deleteJson($resourceUsers . $this->admin->id);
        $response->assertStatus(400)
            ->assertJson(['message' => 'The last user with administrator permissions cannot be deleted.']);

        // Success
        $response = $this->deleteJson($resource);
        $response->assertStatus(200)->assertJson(['message' => 'The user has been deleted successfully.']);
        Notification::assertSentTo(
            [$this->users->first()],
            \App\Notifications\SoftDeleteUser::class
        );
    }

    /**
     * Test [DELETE] users/trashed resource.
     *
     * @covers \App\Http\Controllers\UserController
     * @return void
     */
    public function testDeletePermanently()
    {
        $resource = self::API_URL . 'users/trashed';
        $usersResource = self::API_URL . 'users';

        // Unauthorized
        $this->checkUnauthorized($resource, 'delete');

        // Forbidden
        $this->checkForbidden($resource, 'delete');

        // Sign in as admin
        $this->actingAs($this->admin);

        // Invalid id
        $this->checkInvalidInput($resource, 'delete',  ['ids' => [$this->admin->id]]);

        // Soft delete users first
        $ids = array_map(function ($user) { return $user['id']; }, $this->users->toArray());

        $response = $this->deleteJson($resource, ['ids' => $ids]);
        $response->assertStatus(200)
            ->assertJsonFragment(['count' => 0])
            ->assertJsonFragment(['message' => 'No users have been deleted permanently.']);

        $response = $this->deleteJson($usersResource, ['ids' => $ids]);
        $response->assertStatus(200)
            ->assertJsonFragment(['count' => 8])
            ->assertJsonFragment(['message' => '8 users have been deleted successfully.']);

        $this->users->each(function($user) {
            $this->assertSoftDeleted($user);
        });

        // Success
        $response = $this->deleteJson($resource, ['ids' => $ids]);
        $response->assertStatus(200)
            ->assertJsonFragment(['count' => 8])
            ->assertJsonFragment(['message' => '8 users have been deleted permanently.']);

        $this->users->each(function($user) {
            $this->assertDeleted($user);
        });
    }

    /**
     * Test [GET] /users/:id/qualifications resource.
     *
     * @covers \App\Http\Controllers\UserController
     * @return void
     */
    public function testQualificationsGet()
    {
        $resource = self::API_URL . 'users/' . $this->users->first()->id . '/qualifications';
        $resourceUsers = self::API_URL . 'users/';

        // Attach qualification
        $qualifications = Qualification::factory()->count(2)->create();
        $this->users->first()->qualifications()->attach($qualifications->pluck('slug')->toArray());
        Qualification::factory()->count(2)->create();

        // Unauthorized
        $this->checkUnauthorized($resource);

        // Forbidden
        $this->checkForbidden($resource);

        // Sign in as admin
        $this->actingAs($this->admin);

        // Not found
        $this->checkNotFound($resourceUsers . '9999/qualifications');

        // Success
        $response = $this->getJson($resource);
        $response->assertStatus(200)->assertJson($this->users->first()->qualifications->toArray());
    }

    /**
     * Test [PUT] /users/:id/qualifications resource.
     *
     * @covers \App\Http\Controllers\UserController
     * @return void
     */
    public function testQualificationsUpdate()
    {
        $resource = self::API_URL . 'users/' . $this->users->first()->id . '/qualifications';
        $resourceUsers = self::API_URL . 'users/';

        // Create qualification
        $qualifications = Qualification::factory()->count(2)->create();

        // Unauthorized
        $this->checkUnauthorized($resource, 'put');

        // Forbidden
        $this->checkForbidden($resource, 'put');

        // Sign in as admin
        $this->actingAs($this->admin);

        // Not found
        $this->checkNotFound($resourceUsers . '9999/qualifications');

        // Invalid input
        $this->checkInvalidInput(
            $resource,
            'put',
            ['qualifications' => 'not an array'],
            ['qualifications' => ['The Qualifications must be an array.']]
        );

        // No qualifications
        $this->assertEquals([], $this->users->first()->qualifications->toArray());

        // Success - Add all
        $response = $this->putJson($resource, ['qualifications' => $qualifications->pluck('slug')->toArray()]);
        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'The qualifications have been updated successfully.']);

        // Check database
        $this->assertDatabaseHas('qualification_user', [
            'qualification_slug' => $qualifications->last()->slug, 'user_id' => $this->users->first()->id,
        ]);

        // Success - Sync (only first qualification)
        $response = $this->putJson($resource, ['qualifications' => [$qualifications->first()->slug]]);
        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'The qualifications have been updated successfully.']);

        // Check database
        $this->assertDatabaseHas('qualification_user', [
            'qualification_slug' => $qualifications->first()->slug, 'user_id' => $this->users->first()->id,
        ]);
        $this->assertDatabaseMissing('qualification_user', [
            'qualification_slug' => $qualifications->last()->slug, 'user_id' => $this->users->first()->id,
        ]);
    }

    /**
     * Test [Post] users/:id/restore resource.
     *
     * @covers \App\Http\Controllers\UserController
     * @return void
     */
    public function testRestore()
    {
        $resource = self::API_URL . 'users/' . $this->users->first()->id . '/restore';
        $resourceUsers = self::API_URL . 'users/';

        Notification::fake();

        // Unauthorized
        $this->checkUnauthorized($resource, 'post');

        // Forbidden
        $this->checkForbidden($resource, 'post');

        // Sign in as admin
        $this->actingAs($this->admin);

        // Not found
        $this->checkNotFound($resourceUsers . '9999/restore', 'post');

        // User not deleted
        $response = $this->postJson($resource);
        $response->assertStatus(400)->assertJson(['message' => 'The user is not deleted.']);

        // Soft delete user first
        $this->users->first()->delete();
        $this->assertSoftDeleted($this->users->first());

        // Success
        $response = $this->postJson($resource);
        $response->assertStatus(200)->assertJson(['message' => 'The user has been restored.']);

        // Check notification
        Notification::assertSentTo(
            [$this->users->first()],
            \App\Notifications\RestoreUser::class
        );
    }

    /**
     * Test [PUT] users/trashed resource.
     *
     * @covers \App\Http\Controllers\UserController
     * @return void
     */
    public function testRestoreBulk()
    {
        $resource = self::API_URL . 'users/trashed';
        $usersResource = self::API_URL . 'users';

        Notification::fake();

        // Unauthorized
        $response = $this->putJson($resource, ['ids' => [1]]);
        $response->assertStatus(401)->assertJson(['message' => 'You are not signed in.']);

        // Forbidden
        $this->checkForbidden($resource);

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
            Notification::assertSentTo(
                [$user],
                \App\Notifications\RestoreUser::class
            );
        });
    }

    /**
     * Test [GET] users/trashed resource.
     *
     * @covers \App\Http\Controllers\UserController
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
        $this->checkForbidden($resource);

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

    /**
     * Test [PUT] users/:id resource.
     *
     * @covers \App\Http\Controllers\UserController
     * @return void
     */
    public function testUpdate()
    {
        $resource = self::API_URL . 'users/' . $this->users->first()->id;
        $resourceUsers = self::API_URL . 'users/';
        $json = [
            'dob'               => $this->faker->date($format = 'Y-m-d', $max = 'now'),
            'firstname'         => $this->faker->firstName,
            'gender'            => $this->faker->randomElement(validGender()),
            'is_active'         => $this->faker->boolean(80),
            'lastname'          => $this->faker->lastName,
            'locale'            => 'de',
            'middlename'        => $this->faker->optional(25)->firstName,
            'mobile'            => $this->faker->optional()->phoneNumber,
            'password_change'   => $this->faker->boolean(20),
            'phone'             => $this->faker->optional()->e164PhoneNumber,
            'username'          => $this->faker->unique()->regexify('[A-Za-z0-9]{5,20}'),
            'timezone'          => $this->faker->timezone,
        ];

        Notification::fake();

        // Unauthorized
        $this->checkUnauthorized($resource, 'put');

        // Forbidden
        $this->checkForbidden($resource, 'put');

        // Sign in as admin
        $this->actingAs($this->admin);

        // Not found
        $this->checkNotFound($resourceUsers . '9999', 'put');

        // Invalid input
        $this->checkInvalidInput(
            $resourceUsers . $this->admin->id,
            'put',
            ['is_active' => 0, 'role' => 2],
            [
                'is_active' => ['You cannot disable your own user account.'],
                'role' => ['You cannot change your own user role.']
            ]
        );

        // Success
        $response = $this->putJson($resource, $json);
        $response->assertJsonFragment($json);
        $this->assertDatabaseHas('users', $json);

        // Success role change
        $response = $this->putJson($resource, ['role' => 1]);
        $response->assertStatus(200)->assertJsonFragment(['id' => 1, 'name' => 'Administrator']);
        $this->assertDatabaseHas('users', ['role_id' => 1]);

        // Success with email change
        $newEmail = $this->faker->unique()->freeEmail;
        $response = $this->putJson($resource, ['email' => $newEmail]);
        $response->assertStatus(200)->assertJsonFragment(['email' => $this->users->first()->email]);
        Notification::assertSentTo(
            [$this->users->first()],
            \App\Notifications\VerifyEmail::class
        );
        $this->assertDatabaseHas(
            'email_changes',
            ['email' => $this->users->first()->email, 'new_email' => $newEmail]
        );
    }

    /**
     * Test [GET] users/:id resource.
     *
     * @covers \App\Http\Controllers\UserController
     * @return void
     */
    public function testUser()
    {
        $resource = self::API_URL . 'users/' . $this->users->first()->id;
        $resourceUsers = self::API_URL . 'users/';

        // Unauthorized
        $this->checkUnauthorized($resource);

        // Forbidden
        $this->checkForbidden($resource);

        // Sign in as admin
        $this->actingAs($this->admin);

        // Not found
        $this->checkNotFound($resourceUsers . '9999');

        // Success
        $response = $this->getJson($resource);
        $response->assertStatus(200)->assertJson($this->users->first()->toArray());
    }
}
