<?php

namespace Tests\Feature\Aircraft;

use App\Models\Aircraft;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * Class AircraftResourcesTest
 * @package Tests\Feature\Aircraft
 */
class AircraftResourcesTest extends TestCase
{
    use WithFaker;

    /**
     * @var Aircraft
     */
    protected $aircraft;

    /**
     * @var string
     */
    protected $resource = self::API_URL . 'aircraft';

    public function setUp(): void
    {
        parent::setUp();

        // Create aircraft(s)
        $this->aircraft = Aircraft::factory()->count(3)->create();
    }

    /**
     * Test [GET] aircraft resource.
     *
     * @covers \App\Http\Controllers\AircraftController
     * @return void
     */
    public function testAll()
    {
        // Unauthorized
        $this->checkUnauthorized($this->resource);

        // Forbidden
        $this->checkForbidden($this->resource);

        // Sign in as admin
        $this->actingAs($this->admin);

        // Invalid filtering
        $response = $this->getJson($this->resource . '?filter[invalid]=somevalue');
        $response->assertStatus(400)
            ->assertJsonFragment(['message' => 'Requested filter(s) `invalid` are not allowed. Allowed filter(s) are `dom, dom_at_after, dom_at_before, model, oos, registration, seats, seats_elt, seats_emt`.']);

        // Invalid sorting
        $response = $this->getJson($this->resource . '?sort=invalid');
        $response->assertStatus(400)
            ->assertJsonFragment(['message' => 'Requested sort(s) `invalid` is not allowed. Allowed sort(s) are `dom, model, registration, seats`.']);

        // Success
        $response = $this->getJson($this->resource);
        $response
            ->assertStatus(200)
            ->assertJsonFragment(['per_page' => 50])
            ->assertJsonFragment(['total' => Aircraft::withTrashed()->get()->count()]);

        // Filtering
        $response = $this
            ->getJson($this->resource . '?filter[registration]=' . $this->aircraft->first()->registration);
        $count = Aircraft::withTrashed()->where(
            'registration',
            'like',
            '%' .  $this->aircraft->first()->registration . '%')->count();
        $response->assertStatus(200)->assertJsonFragment(['total' => $count]);
    }

    /**
     * Test [POST] aircraft resource.
     *
     * @covers \App\Http\Controllers\AircraftController
     * @return void
     */
    public function testCreate()
    {
        $json = [
            'dom'          => $this->faker->date($format = 'Y-m-d', $max = 'now'),
            'model'        => 'Cessna 182',
            'registration' => $this->faker->unique()->regexify('D-[EG][A-Z]{3}'),
            'seats'        => 4,
        ];

        // Unauthorized
        $this->checkUnauthorized($this->resource, 'post');

        // Forbidden
        $this->checkForbidden($this->resource, 'post');

        // Sign in as admin
        $this->actingAs($this->admin);

        // Invalid input
        $this->checkInvalidInput($this->resource, 'post', ['dom' => 0], [
            'dom'          => ['The Manufacturing Date is not a valid date.'],
            'model'        => ['The Model field is required.'],
            'registration' => ['The Aircraft Registration field is required.'],
            'seats'        => ['The Seats field is required.'],
        ]);

        // Success
        $response = $this->postJson($this->resource, $json);
        $response->assertStatus(201)->assertJson(['message' => 'The aircraft has been created successfully.']);

        // Check database
        $this->assertDatabaseHas('aircraft', $json);

        // Check if logbook has been created
        $aircraft = Aircraft::find($json['registration']);
        $this->assertNotNull($aircraft->logbook);
        $this->assertDatabaseHas('aircraft_logbooks', ['aircraft_registration' => $json['registration']]);
    }

    /**
     * Test [GET] aircraft/:aircraftRegistration resource.
     *
     * @covers \App\Http\Controllers\AircraftController
     * @return void
     */
    public function testGet()
    {
        $resource = $this->resource . '/' . $this->aircraft->first()->registration;

        // Unauthorized
        $this->checkUnauthorized($resource);

        // Forbidden
        $this->checkForbidden($resource);

        // Sign in as admin
        $this->actingAs($this->admin);

        // Not found
        $this->checkNotFound($this->resource . '/E-XXXX');

        // Success
        $response = $this->getJson($resource);
        $response->assertStatus(200)
            ->assertJson(Aircraft::withTrashed()->find($this->aircraft->first()->registration)
                ->toArray());
    }

    /**
     * Test [PUT] aircraft/:aircraftRegistration/put-back-into-service resource.
     *
     * @covers \App\Http\Controllers\AircraftController
     * @return void
     */
    public function testPutBackIntoService()
    {
        $aircraftOperating = Aircraft::factory()->putBackIntoService()->create();
        $resourceOperating = $this->resource . '/' . $aircraftOperating->registration . '/put-back-into-service';
        $aircraftNotOperating = Aircraft::factory()->putOutOfService()->create();
        $resourceNotOperating = $this->resource . '/' . $aircraftNotOperating->registration . '/put-back-into-service';

        // Unauthorized
        $this->checkUnauthorized($resourceNotOperating, 'put');

        // Forbidden
        $this->checkForbidden($resourceNotOperating, 'put');

        // Sign in as admin
        $this->actingAs($this->admin);

        // Not found
        $this->checkNotFound($this->resource . '/E-XXXX/put-back-into-service', 'put');

        // Still in service
        $response = $this->putJson($resourceOperating);
        $response->assertStatus(400)->assertJson(['message' => 'The aircraft is still in service.']);

        // Success
        $response = $this->putJson($resourceNotOperating);
        $response->assertStatus(200)->assertJson(['message' => 'The aircraft has been put back into service.']);
    }

    /**
     * Test [PUT] aircraft/:aircraftRegistration/put-out-of-service resource.
     *
     * @covers \App\Http\Controllers\AircraftController
     * @return void
     */
    public function testPutOutOfService()
    {
        $aircraftOperating = Aircraft::factory()->putBackIntoService()->create();
        $resourceOperating = $this->resource . '/' . $aircraftOperating->registration . '/put-out-of-service';
        $aircraftNotOperating = Aircraft::factory()->putOutOfService()->create();
        $resourceNotOperating = $this->resource . '/' . $aircraftNotOperating->registration . '/put-out-of-service';

        // Unauthorized
        $this->checkUnauthorized($resourceOperating, 'put');

        // Forbidden
        $this->checkForbidden($resourceOperating, 'put');

        // Sign in as admin
        $this->actingAs($this->admin);

        // Not found
        $this->checkNotFound($this->resource . '/E-XXXX/put-out-of-service', 'put');

        // Already out of servive
        $response = $this->putJson($resourceNotOperating);
        $response->assertStatus(400)
            ->assertJson(['message' => 'The aircraft has been put out of service already.']);

        // Success
        $response = $this->putJson($resourceOperating);
        $response->assertStatus(200)->assertJson(['message' => 'The aircraft has been put out of service.']);
    }

    /**
     * Test [PUT] aircraft/:aircraftRegistration resource.
     *
     * @covers \App\Http\Controllers\CountryController
     * @return void
     */
    public function testUpdate()
    {
        $resource = $this->resource . '/' . $this->aircraft->first()->registration;

        // Unauthorized
        $this->checkUnauthorized($resource, 'put');

        // Forbidden
        $this->checkForbidden($resource, 'put');

        // Sign in as admin
        $this->actingAs($this->admin);

        // Not found
        $this->checkNotFound($this->resource . '/E-XXXX', 'put');

        // Invalid input
        $this->checkInvalidInput($resource, 'put', ['registration' => $this->aircraft->last()->registration], [
            'registration' => ['The Aircraft Registration has already been taken.']
        ]);

        // Success
        $seats = $this->faker->numberBetween(10, 20);
        $response = $this->putJson($resource, ['seats' => $seats]);
        $response->assertStatus(200)
            ->assertJsonFragment(['seats' => $seats]);

        // Check database
        $this->assertDatabaseHas('aircraft', [
            'registration' => $this->aircraft->first()->registration,
            'seats' => $seats,
        ]);
    }
}
