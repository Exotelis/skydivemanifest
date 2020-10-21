<?php

namespace Tests\Feature\Region;

use App\Models\Country;
use App\Models\Region;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * Class RegionResourcesTest
 * @package Tests\Feature\Region
 */
class RegionResourcesTest extends TestCase
{
    use WithFaker;

    /**
     * @var Country
     */
    protected $country;

    /**
     * @var Region
     */
    protected $regions;

    /**
     * Base resource to manage regions.
     *
     * @var string
     */
    protected $resource;

    /**
     * Resource of a country that does not exist.
     *
     * @var string
     */
    protected $countryNotFoundResource = self::API_URL . 'countries/9999/regions';

    public function setUp(): void
    {
        parent::setUp();

        // Create a country
        $this->country = Country::factory()->create();

        // Create regions
        $this->regions = Region::factory()->count(10)->create(['country_id' => $this->country->id]);

        $this->resource = self::API_URL . 'countries/' . $this->country->id . '/regions';
    }

    /**
     * Test [GET] countries/:countryID/regions resource.
     *
     * @covers \App\Http\Controllers\RegionController
     * @return void
     */
    public function testAll()
    {
        // Unauthorized
        $this->checkUnauthorized($this->resource);

        // Forbidden - Reading regions is a default permission. Therefore, set permissions manually.
        $this->actingAs($this->admin, ['invalid']);
        $response = $this->getJson($this->resource);
        $response->assertStatus(403)->assertJson(['message' => 'Invalid scope(s) provided.']);

        // Sign in as admin
        $this->actingAs($this->admin);

        // Not found - country
        $this->checkNotFound($this->countryNotFoundResource);

        // Invalid filtering
        $response = $this->getJson($this->resource . '?filter[invalid]=somevalue');
        $response->assertStatus(400)
            ->assertJsonFragment(['message' => 'Requested filter(s) `invalid` are not allowed. Allowed filter(s) are `region`.']);

        // Invalid sorting
        $response = $this->getJson($this->resource . '?sort=invalid');
        $response->assertStatus(400)
            ->assertJsonFragment(['message' => 'Requested sort(s) `invalid` is not allowed. Allowed sort(s) are `id, region`.']);

        // Success
        $response = $this->getJson($this->resource);
        $response->assertStatus(200)
            ->assertJsonFragment(['per_page' => 50])
            ->assertJsonFragment(['total' => Region::all()->count()]);

        // Filtering
        $response = $this->getJson($this->resource . '?filter[region]=' . $this->regions->first()->region);
        $count = Region::where('region', 'like', '%' .  $this->regions->first()->region . '%')
            ->count();
        $response->assertStatus(200)
            ->assertJsonFragment(['total' => $count]);
    }

    /**
     * Test [POST] countries/:countryID/regions resource.
     *
     * @covers \App\Http\Controllers\RegionController
     * @return void
     */
    public function testCreate()
    {
        $json = [
            'region' => $this->faker->unique()->state,
        ];
        $invalidJson = [
            'region' => $this->regions->first()->region,
        ];

        // Unauthorized
        $this->checkUnauthorized($this->resource, 'post');

        // Forbidden
        $this->checkForbidden($this->resource, 'post');

        // Sign in as admin
        $this->actingAs($this->admin);

        // Not found - country
        $this->checkNotFound($this->countryNotFoundResource, 'post', $json);

        // Invalid input - Region is not related to country
        $this->checkInvalidInput($this->resource, 'post', $invalidJson, [
            'region' => ["The region has already been taken."]
        ]);

        // Success
        $response = $this->postJson($this->resource, $json);
        $response->assertStatus(201)->assertJson(['message' => 'The region has been created successfully.']);

        // Check database
        $this->assertDatabaseHas('regions', $json);
    }

    /**
     * Test [DELETE] countries/:countryID/regions/:regionID resource.
     *
     * @covers \App\Http\Controllers\RegionController
     * @return void
     */
    public function testDelete()
    {
        $resource = $this->resource . '/' . $this->regions->first()->id;

        // Unauthorized
        $this->checkUnauthorized($resource, 'delete');

        // Forbidden
        $this->checkForbidden($resource, 'delete');

        // Sign in as admin
        $this->actingAs($this->admin);

        // Not found - country
        $this->checkNotFound(
            $this->countryNotFoundResource . '/' . $this->regions->first()->id,
            'delete'
        );

        // Not found - region
        $this->checkNotFound($this->resource . '/9999', 'delete');

        // Not found - region of different country
        $country = Country::factory()->create();
        $region = Region::factory()->create(['country_id' => $country->id]);
        $this->checkNotFound($this->resource . '/' . $region->id, 'delete');

        // Success
        $response = $this->deleteJson($resource);
        $response->assertStatus(200)->assertJson(['message' => 'The region has been deleted successfully.']);
        $this->assertDeleted($this->regions->first());
    }

    /**
     * Test [DELETE] countries/:countryID/regions resource.
     *
     * @covers \App\Http\Controllers\RegionController
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

        // Not found - country
        $this->checkNotFound($this->countryNotFoundResource, 'delete', ['ids' => ['1']]);

        // Invalid input
        $this->checkInvalidInput($this->resource, 'delete', [], [
            'ids' => ["The ids field is required."]
        ]);

        // Success
        $response = $this->deleteJson($this->resource, ['ids' => $this->regions->pluck('id')->toArray()]);
        $response->assertStatus(200)->assertJson(['message' => '10 regions have been deleted successfully.']);
        foreach ($this->regions as $region) {
            $this->assertDeleted($region);
        }
    }

    /**
     * Test [GET] countries/:countryID/regions/:regionID resource.
     *
     * @covers \App\Http\Controllers\RegionController
     * @return void
     */
    public function testGet()
    {
        $resource = $this->resource . '/' . $this->regions->first()->id;

        // Unauthorized
        $this->checkUnauthorized($resource);

        // Forbidden - Reading regions is a default permission. Therefore, set permissions manually.
        $this->actingAs($this->admin, ['invalid']);
        $response = $this->getJson($this->resource);
        $response->assertStatus(403)->assertJson(['message' => 'Invalid scope(s) provided.']);

        // Sign in as admin
        $this->actingAs($this->admin);

        // Not found - country
        $this->checkNotFound($this->countryNotFoundResource . '/' . $this->regions->first()->id);

        // Not found - region
        $this->checkNotFound($this->resource . '/9999');

        // Not found - region of different country
        $country = Country::factory()->create();
        $region = Region::factory()->create(['country_id' => $country->id]);
        $this->checkNotFound($this->resource . '/' . $region->id);

        // Success
        $response = $this->getJson($resource);;
        $response->assertStatus(200)->assertJson(Region::find($this->regions->first()->id)->toArray());
    }

    /**
     * Test [PUT] countries/:countryID/regions/:regionID resource.
     *
     * @covers \App\Http\Controllers\RegionController
     * @return void
     */
    public function testUpdate()
    {
        $resource = $this->resource . '/' . $this->regions->first()->id;

        // Unauthorized
        $this->checkUnauthorized($resource, 'put');

        // Forbidden
        $this->checkForbidden($resource, 'put');

        // Sign in as admin
        $this->actingAs($this->admin);

        // Not found - country
        $this->checkNotFound($this->countryNotFoundResource . '/' . $this->regions->first()->id, 'put');

        // Not found - region
        $this->checkNotFound($this->resource . '/9999', 'put');

        // Not found - region of different country
        $country = Country::factory()->create();
        $region = Region::factory()->create(['country_id' => $country->id]);
        $this->checkNotFound($this->resource . '/' . $region->id, 'put');

        // Invalid input
        $this->checkInvalidInput($resource, 'put', ['region' => $this->regions->last()->region], [
            'region' => ['The region has already been taken.']
        ]);

        // Success
        $region = $this->faker->unique()->state;
        $response = $this->putJson($resource, ['region' => $region]);
        $response->assertStatus(200)->assertJsonFragment(['region' => $region]);

        // Check database
        $this->assertDatabaseHas('regions', [
            'id' => $this->regions->first()->id,
            'region' => $region,
        ]);

        // Success with same region
        $response = $this->putJson($resource, ['region' => $region]);
        $response->assertStatus(200)->assertJsonFragment(['region' => $region]);
    }
}
