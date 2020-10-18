<?php

namespace Tests\Feature\Country;

use App\Models\Country;
use App\Models\Region;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * Class CountryResourcesTest
 * @package Tests\Feature\Country
 */
class CountryResourcesTest extends TestCase
{
    use WithFaker;

    /**
     * @var Country
     */
    protected $countries;

    /**
     * @var string
     */
    protected $resource = self::API_URL . 'countries';

    public function setUp(): void
    {
        parent::setUp();

        // Create countries and regions
        $this->countries = Country::factory()->count(3)->create()->each(function($country) {
            Region::factory()->count(10)->create([
                'country_id' => $country->id,
            ]);
        });
    }

    /**
     * Test [GET] countries resource.
     *
     * @covers \App\Http\Controllers\CountryController
     * @return void
     */
    public function testAll()
    {
        // Unauthorized
        $this->checkUnauthorized($this->resource);

        // Forbidden - Reading countries is a default permission. Therefore, set permissions manually.
        $this->actingAs($this->admin, ['invalid']);
        $response = $this->getJson($this->resource);
        $response->assertStatus(403)->assertJson(['message' => 'Invalid scope(s) provided.']);

        // Sign in as admin
        $this->actingAs($this->admin);

        // Invalid filtering
        $response = $this->getJson($this->resource . '?filter[invalid]=somevalue');
        $response->assertStatus(400)
            ->assertJsonFragment(['message' => 'Requested filter(s) `invalid` are not allowed. Allowed filter(s) are `code, country`.']);

        // Invalid sorting
        $response = $this->getJson($this->resource . '?sort=invalid');
        $response->assertStatus(400)
            ->assertJsonFragment(['message' => 'Requested sort(s) `invalid` is not allowed. Allowed sort(s) are `id, code, country`.']);

        // Success
        $response = $this->getJson($this->resource);
        $response
            ->assertStatus(200)
            ->assertJsonFragment(['per_page' => 50])
            ->assertJsonFragment(['total' => Country::all()->count()]);

        // Filtering
        $response = $this->getJson($this->resource . '?filter[code]=' . $this->countries->first()->code);
        $count = Country::where('code', 'like', '%' .  $this->countries->first()->code . '%')
            ->count();
        $response
            ->assertStatus(200)
            ->assertJsonFragment(['total' => $count]);
    }

    /**
     * Test [POST] countries resource.
     *
     * @covers \App\Http\Controllers\CountryController
     * @return void
     */
    public function testCreate()
    {
        $json = [
            'country' => $this->faker->unique()->country,
            'code'    => $this->faker->unique()->countryCode,
        ];

        // Unauthorized
        $this->checkUnauthorized($this->resource, 'post');

        // Forbidden
        $this->checkForbidden($this->resource, 'post');

        // Sign in as admin
        $this->actingAs($this->admin);

        // Invalid input
        $this->checkInvalidInput($this->resource, 'post', ['code' => 'en'], [
            'country' => ["The Country field is required."]
        ]);

        // Success
        $response = $this->postJson($this->resource, $json);
        $response->assertStatus(201)->assertJson(['message' => 'The country has been created successfully.']);

        // Check database
        $this->assertDatabaseHas('countries', $json);
    }

    /**
     * Test [DELETE] countries/:countryID resource.
     *
     * @covers \App\Http\Controllers\CountryController
     * @return void
     */
    public function testDelete()
    {
        $resource = $this->resource . '/' . $this->countries->first()->id;

        // Unauthorized
        $this->checkUnauthorized($resource, 'delete');

        // Forbidden
        $this->checkForbidden($resource, 'delete');

        // Sign in as admin
        $this->actingAs($this->admin);

        // Not found
        $this->checkNotFound($this->resource . '/9999', 'delete');

        // Success
        $regions = $this->countries->first()->regions;
        $response = $this->deleteJson($resource);
        $response->assertStatus(200)->assertJson(['message' => 'The country has been deleted successfully.']);
        $this->assertDeleted($this->countries->first());

        // Check if regions of deleted country are also delete
        foreach ($regions as $region) {
            $this->assertDeleted($region);
        }
    }

    /**
     * Test [DELETE] countries resource.
     *
     * @covers \App\Http\Controllers\CountryController
     * @return void
     */
    public function testDeleteBulk()
    {
        // Unauthorized
        $this->checkUnauthorized($this->resource, 'delete');

        // Forbidden
        $this->checkForbidden($this->resource, 'delete');

        // Sign in as admin
        $this->actingAs($this->admin);

        // Invalid input
        $this->checkInvalidInput($this->resource, 'delete', [], [
            'ids' => ["The ids field is required."]
        ]);

        // Success
        $response = $this->deleteJson($this->resource, ['ids' => $this->countries->pluck('id')->toArray()]);
        $response->assertStatus(200)->assertJson(['message' => '3 countries have been deleted successfully.']);
        foreach ($this->countries as $country) {
            $this->assertDeleted($country);
        }
    }

    /**
     * Test [GET] countries/:countryID resource.
     *
     * @covers \App\Http\Controllers\CountryController
     * @return void
     */
    public function testGet()
    {
        $resource = $this->resource . '/' . $this->countries->first()->id;

        // Unauthorized
        $this->checkUnauthorized($resource);

        // Forbidden - Reading countries is a default permission. Therefore, set permissions manually.
        $this->actingAs($this->admin, ['invalid']);
        $response = $this->getJson($resource);
        $response->assertStatus(403)->assertJson(['message' => 'Invalid scope(s) provided.']);

        // Sign in as admin
        $this->actingAs($this->admin);

        // Not found
        $this->checkNotFound($this->resource . '/9999');

        // Success
        $response = $this->getJson($resource);;
        $response->assertStatus(200)->assertJson(Country::find($this->countries->first()->id)->toArray());
    }

    /**
     * Test [PUT] countries/:countryID resource.
     *
     * @covers \App\Http\Controllers\CountryController
     * @return void
     */
    public function testUpdate()
    {
        $resource = $this->resource . '/' . $this->countries->first()->id;

        // Unauthorized
        $this->checkUnauthorized($resource, 'put');

        // Forbidden
        $this->checkForbidden($resource, 'put');

        // Sign in as admin
        $this->actingAs($this->admin);

        // Not found
        $this->checkNotFound($this->resource . '/9999', 'put');

        // Invalid input
        $this->checkInvalidInput($resource, 'put', ['code' => $this->countries->last()->code], [
            'code' => ['The Code has already been taken.']
        ]);

        // Success
        $code = $this->faker->unique()->countryCode;
        $response = $this->putJson($resource, ['code' => $code, 'country' => $this->countries->first()->country]);
        $response->assertStatus(200)
            ->assertJsonFragment(['code' => $code]);

        // Check database
        $this->assertDatabaseHas('countries', [
            'id' => $this->countries->first()->id,
            'code' => $code,
        ]);
    }
}
