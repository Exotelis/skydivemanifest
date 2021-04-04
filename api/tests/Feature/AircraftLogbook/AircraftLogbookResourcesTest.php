<?php

namespace Tests\Feature\AircraftLogbook;

use App\Models\Aircraft;
use App\Models\AircraftLogbook;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Tests\TestCase;

/**
 * Class AircraftLogbookResourcesTest
 * @package Tests\Feature\AircraftLogbook
 */
class AircraftLogbookResourcesTest extends TestCase
{
    use WithFaker;

    /**
     * @var AircraftLogbook
     */
    protected $logbook;

    /**
     * @var string
     */
    protected $resource;

    /**
     * @var string
     */
    protected $aircraftNotFoundResource = self::API_URL . 'aircraft/X-XXXX/logbook';

    public function setUp(): void
    {
        parent::setUp();

        // Create aircraft logbook
        $this->logbook = AircraftLogbook::factory()->for(Aircraft::factory()->putBackIntoService())->create();
        $registration = $this->logbook->aircraft->registration;

        $this->resource = self::API_URL . 'aircraft/' . $registration . '/logbook';
    }

    /**
     * Test [GET] aircraft/:aircraftRegistration/logbook resource.
     *
     * @covers \App\Http\Controllers\AircraftLogbookController
     * @return void
     */
    public function testGet()
    {
        // Unauthorized
        $this->checkUnauthorized($this->resource);

        // Forbidden
        $this->checkForbidden($this->resource);

        // Sign in as admin
        $this->actingAs($this->admin);

        // Not found - aircraft
        $this->checkNotFound($this->aircraftNotFoundResource);

        // Success
        $response = $this->getJson($this->resource);
        $response->assertStatus(200)->assertJson(Arr::except($this->logbook->toArray(), ['aircraft']));
    }

    /**
     * Test [PUT] aircraft/:aircraftRegistration/logbook resource.
     *
     * @covers \App\Http\Controllers\AircraftLogbookController
     * @return void
     */
    public function testUpdate()
    {
        // Unauthorized
        $this->checkUnauthorized($this->resource, 'put');

        // Forbidden
        $this->checkForbidden($this->resource, 'put');

        // Sign in as admin
        $this->actingAs($this->admin);

        // Not found - aircraft
        $this->checkNotFound($this->aircraftNotFoundResource, 'put');

        // Invalid input
        $this->checkInvalidInput($this->resource, 'put', [
            'transfer' => 'invalid',
        ], [
            'transfer' => ['The Transfer must be an integer.'],
        ]);

        // Success
        $transfer = $this->faker->numberBetween(50000, 100000);
        $response = $this->putJson($this->resource, ['transfer' => $transfer]);
        $response->assertStatus(200)->assertJsonFragment(['transfer' => $transfer]);

        // Check database
        $this->assertDatabaseHas('aircraft_logbooks', [
            'id'       => $this->logbook->id,
            'transfer' => $transfer,
        ]);
    }
}
