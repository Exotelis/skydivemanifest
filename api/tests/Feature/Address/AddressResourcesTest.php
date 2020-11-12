<?php

namespace Tests\Feature\Address;

use App\Models\Address;
use App\Models\Country;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * Class AddressResourcesTest
 * @package Tests\Feature\Address
 */
class AddressResourcesTest extends TestCase
{
    use WithFaker;

    /**
     * @var Address
     */
    protected $addresses;

    /**
     * @var Country
     */
    protected $countries;

    /**
     * @var User
     */
    protected $user;

    protected $baseResource;
    protected $userNotFoundResource = self::API_URL . 'users/9999/addresses';

    public function setUp(): void
    {
        parent::setUp();

        // Create a user with addresses
        $this->user = User::factory()->hasAddresses(5)->create();

        // Get addresses
        $this->addresses = $this->user->addresses;

        // Get countries
        $this->countries = Country::all();

        $this->baseResource = self::API_URL . 'users/' . $this->user->id . '/addresses';
    }

    /**
     * Test [GET] users/:userID/addresses resource.
     *
     * @covers \App\Http\Controllers\AddressController
     * @return void
     */
    public function testAll()
    {
        // Unauthorized
        $this->checkUnauthorized($this->baseResource);

        // Forbidden
        $this->checkForbidden($this->baseResource);

        // Sign in as admin
        $this->actingAs($this->admin);

        // User not found
        $this->checkNotFound($this->userNotFoundResource, 'get');

        // Invalid filtering
        $response = $this->getJson($this->baseResource . '?filter[invalid]=somevalue');
        $response->assertStatus(400)
            ->assertJsonFragment(['message' => 'Requested filter(s) `invalid` are not allowed. Allowed filter(s) are `city, company, country, firstname, lastname, middlename, postal, region, street`.']);

        // Invalid sorting
        $response = $this->getJson($this->baseResource . '?sort=invalid');
        $response->assertStatus(400)
            ->assertJsonFragment(['message' => 'Requested sort(s) `invalid` is not allowed. Allowed sort(s) are `id, city, company, firstname, lastname, middlename, postal, street, country, region`.']);

        // Success
        $response = $this->getJson($this->baseResource);
        $response
            ->assertStatus(200)
            ->assertJsonFragment(['per_page' => 50])
            ->assertJsonFragment(['total' => Address::all()->count()]);

        // Filtering
        $response = $this->getJson($this->baseResource . '?filter[postal]=' . $this->addresses->first()->postal);
        $count = Address::where('postal', 'like', '%' .  $this->addresses->first()->postal . '%')
            ->count();
        $response
            ->assertStatus(200)
            ->assertJsonFragment(['total' => $count]);
    }

    /**
     * Test [POST] users/:userID/addresses resource.
     *
     * @covers \App\Http\Controllers\AddressController
     * @return void
     */
    public function testCreate()
    {
        // Remove default addresses of user.
        $this->user->defaultInvoice()->dissociate();
        $this->user->defaultShipping()->dissociate();
        $this->user->save();

        $json = [
            'city'             => $this->faker->city,
            'company'          => $this->faker->company,
            'country_id'       => $this->countries->first()->id,
            'default_invoice'  => true,
            'default_shipping' => false,
            'firstname'        => $this->faker->firstName,
            'lastname'         => $this->faker->lastName,
            'middlename'       => $this->faker->firstName,
            'postal'           => $this->faker->postcode,
            'region_id'        => $this->countries->first()->regions->first()->id,
            'street'           => $this->faker->streetAddress,
        ];
        $invalidJson = $json;
        $invalidJson['region_id'] = $this->countries->last()->regions->first()->id;

        // Unauthorized
        $this->checkUnauthorized($this->baseResource, 'post');

        // Forbidden
        $this->checkForbidden($this->baseResource, 'post');

        // Sign in as admin
        $this->actingAs($this->admin);

        // User not found
        $this->checkNotFound($this->userNotFoundResource, 'post');

        // Invalid input - Region is not related to country
        $this->checkInvalidInput($this->baseResource, 'post', $invalidJson, [
            'region_id' => ["The selected Region is invalid."]
        ]);

        // Success
        $response = $this->postJson($this->baseResource, $json);
        $response->assertStatus(201)->assertJson(['message' => 'The address has been created successfully.']);

        // Remove from JSON to compare with database
        unset($json['default_invoice'], $json['default_shipping']);
        $this->assertDatabaseHas('addresses', $json);

        // Check if the default addresses of the user has been updated
        $this->user->refresh();
        $this->assertEquals($response['data']['id'], $this->user->default_invoice);
        $this->assertEquals(null, $this->user->default_shipping);
    }

    /**
     * Test [DELETE] users/:userID/addresses/:addressID resource.
     *
     * @covers \App\Http\Controllers\AddressController
     * @return void
     */
    public function testDelete()
    {
        $resource = $this->baseResource . '/' . $this->addresses->first()->id;

        // Unauthorized
        $this->checkUnauthorized($resource, 'delete');

        // Forbidden
        $this->checkForbidden($resource, 'delete');

        // Sign in as admin
        $this->actingAs($this->admin);

        // Not found - user
        $this->checkNotFound($this->userNotFoundResource . '/' . $this->addresses->first()->id, 'delete');

        // Not found - address
        $this->checkNotFound($this->baseResource . '/9999', 'delete');

        // Not found - address of different user
        $address = Address::factory()->create(['user_id' => $this->admin->id]);
        $this->checkNotFound($this->baseResource . '/' . $address->id, 'delete');

        // Success
        $response = $this->deleteJson($resource);
        $response->assertStatus(200)->assertJson(['message' => 'The address has been deleted successfully.']);
        $this->assertDeleted($this->addresses->first());
    }

    /**
     * Test [DELETE] users/:userID/addresses resource.
     *
     * @covers \App\Http\Controllers\AddressController
     * @return void
     */
    public function testDeleteBulk()
    {
        // Unauthorized
        $this->checkUnauthorized($this->baseResource, 'delete');

        // Forbidden
        $this->checkForbidden($this->baseResource, 'delete');

        // Sign in as admin
        $this->actingAs($this->admin);

        // User not found
        $this->checkNotFound($this->userNotFoundResource, 'delete');

        // Invalid input
        $this->checkInvalidInput($this->baseResource, 'delete', [], [
            'ids' => ["The ids field is required."]
        ]);

        // Success
        $response = $this->deleteJson($this->baseResource, ['ids' => $this->addresses->pluck('id')->toArray()]);
        $response->assertStatus(200)->assertJson(['message' => '5 addresses have been deleted successfully.']);
        foreach ($this->addresses as $address) {
            $this->assertDeleted($address);
        }
    }

    /**
     * Test [GET] users/:userID/addresses/:addressID resource.
     *
     * @covers \App\Http\Controllers\AddressController
     * @return void
     */
    public function testGet()
    {
        $resource = $this->baseResource . '/' . $this->addresses->first()->id;

        // Unauthorized
        $this->checkUnauthorized($resource);

        // Forbidden
        $this->checkForbidden($resource);

        // Sign in as admin
        $this->actingAs($this->admin);

        // Not found - user
        $this->checkNotFound($this->userNotFoundResource . '/' . $this->addresses->first()->id);

        // Not found - address
        $this->checkNotFound($this->baseResource . '/9999');

        // Not found - address of different user
        $address = Address::factory()->create(['user_id' => $this->admin->id]);
        $this->checkNotFound($this->baseResource . '/' . $address->id);

        // Success
        $response = $this->getJson($resource);
        $response->assertStatus(200)->assertJson(Address::find($this->addresses->first()->id)->toArray());
    }

    /**
     * Test [PUT] users/:userID/addresses/:addressID resource.
     *
     * @covers \App\Http\Controllers\AddressController
     * @return void
     */
    public function testUpdate()
    {
        $resource = $this->baseResource . '/' . $this->addresses->first()->id;

        // Unauthorized
        $this->checkUnauthorized($resource, 'put');

        // Forbidden
        $this->checkForbidden($resource, 'put');

        // Sign in as admin
        $this->actingAs($this->admin);

        // Not found - user
        $this->checkNotFound($this->userNotFoundResource . '/' . $this->addresses->first()->id, 'put');

        // Not found - address
        $this->checkNotFound($this->baseResource . '/9999', 'put');

        // Not found - address of different user
        $address = Address::factory()->create(['user_id' => $this->admin->id]);
        $this->checkNotFound($this->baseResource . '/' . $address->id, 'put');

        // Invalid input
        $this->checkInvalidInput($resource, 'put', ['country_id' => 2], [
            'region_id' => ['The Region field is required when Country is present.']
        ]);

        // Success
        $middlename = $this->faker->firstName;
        $response = $this->putJson($resource, ['middlename' => $middlename]);
        $response->assertStatus(200)
            ->assertJsonFragment(['middlename' => $middlename]);
        $this->assertDatabaseHas('addresses', [
            'id' => $this->addresses->first()->id,
            'middlename' => $middlename,
        ]);
    }
}
