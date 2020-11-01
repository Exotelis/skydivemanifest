<?php

namespace Tests\Feature\AircraftMaintenance;

use App\Models\Aircraft;
use App\Models\AircraftMaintenance;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * Class AircraftMaintenanceResourcesTest
 * @package Tests\Feature\AircraftMaintenance
 */
class AircraftMaintenanceResourcesTest extends TestCase
{
    use WithFaker;

    /**
     * @var AircraftMaintenance|\Illuminate\Database\Eloquent\Collection
     */
    protected $maintenance;

    /**
     * @var string
     */
    protected $resource;

    /**
     * @var string
     */
    protected $aircraftNotFoundResource = self::API_URL . 'aircraft/X-XXXX/maintenance';

    public function setUp(): void
    {
        parent::setUp();

        // Create aircraft maintenance(s)
        $this->maintenance = AircraftMaintenance::factory()
            ->count(3)
            ->for(Aircraft::factory())
            ->notMaintained()
            ->create();
        $registration = $this->maintenance->first()->aircraft->registration;

        $this->resource = self::API_URL . 'aircraft/' . $registration . '/maintenance';
    }

    /**
     * Test [GET] aircraft/:aircraftRegistration/maintenance resource.
     *
     * @covers \App\Http\Controllers\AircraftMaintenanceController
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

        // Not found - aircraft
        $this->checkNotFound($this->aircraftNotFoundResource);

        // Invalid filtering
        $response = $this->getJson($this->resource . '?filter[invalid]=somevalue');
        $response->assertStatus(400)
            ->assertJsonFragment(['message' => 'Requested filter(s) `invalid` are not allowed. Allowed filter(s) are `dom, dom_at_after, dom_at_before, maintenance_at, maintenance_at_elt, maintenance_at_emt, name, notes, notified, notify_at, notify_at_elt, notify_at_emt, repetition_interval`.']);

        // Invalid sorting
        $response = $this->getJson($this->resource . '?sort=invalid');
        $response->assertStatus(400)
            ->assertJsonFragment(['message' => 'Requested sort(s) `invalid` is not allowed. Allowed sort(s) are `dom, id, maintenance, name, notified, notify, repetition_interval`.']);

        // Success
        $response = $this->getJson($this->resource);
        $response->assertStatus(200)
            ->assertJsonFragment(['per_page' => 50])
            ->assertJsonFragment(['total' => AircraftMaintenance::all()->count()]);

        // Filtering
        $response = $this->getJson($this->resource . '?filter[name]=' . $this->maintenance->first()->name);
        $count = AircraftMaintenance::where(
            'name',
            'like',
            '%' .  $this->maintenance->first()->name . '%')->count();
        $response->assertStatus(200)->assertJsonFragment(['total' => $count]);
    }

    /**
     * Test [PUT] aircraft/:aircraftRegistration/maintenance/:maintenanceID/complete resource.
     *
     * @covers \App\Http\Controllers\AircraftMaintenanceController
     * @return void
     */
    public function testComplete()
    {
        $resource = $this->resource . '/' . $this->maintenance->first()->id . '/complete';

        // Unauthorized
        $this->checkUnauthorized($resource, 'put');

        // Forbidden
        $this->checkForbidden($resource, 'put');

        // Sign in as admin
        $this->actingAs($this->admin);

        // Bad Request - Already completed
        $maintenance = AircraftMaintenance::factory()->for(Aircraft::factory())->maintained()->create();
        $badRequestResource = self::API_URL . 'aircraft/' . $maintenance->aircraft->registration . '/maintenance/'
            . $maintenance->id . '/complete';
        $this->checkBadRequest(
            $badRequestResource,
            'The maintenance of the aircraft has already been marked as completed.',
            'put'
        );

        // Not found - aircraft
        $this->checkNotFound(
            $this->aircraftNotFoundResource . '/' . $this->maintenance->first()->id . '/complete',
            'put'
        );

        // Not found - maintenance
        $this->checkNotFound($this->resource . '/9999/complete', 'put');

        // Not found - maintenance of different aircraft
        $maintenance = AircraftMaintenance::factory()->for(Aircraft::factory())->create();
        $this->checkNotFound($this->resource . '/' . $maintenance->id . '/complete', 'put');

        // Invalid input
        $flightTime = $this->maintenance->first()->aircraft->flight_time;
        $this->checkInvalidInput($resource, 'put', [
            'dom'         => 'invalid',
            'flight_time' => 0,
        ], [
            'dom'            => ['The Maintenance date is not a valid date.'],
            'flight_time'    => ['The Flight Time must be greater than or equal ' . $flightTime . '.'],
            'maintenance_at' => ['The Maintenance at field is required.'],
        ]);

        // Success
        $date = Carbon::now()->subDay();
        $flightTime = $this->maintenance->first()->aircraft->flight_time + 100;
        $response = $this->putJson($resource, [
            'dom'            => $date,
            'flight_time'    => $flightTime,
            'maintenance_at' => $flightTime,
        ]);
        $response->assertStatus(200)
            ->assertJsonFragment(['dom' => $date->toDateString(), 'flight_time' => $flightTime]);

        // Check database
        $this->assertDatabaseHas('aircraft', [
            'registration' => $this->maintenance->first()->aircraft->registration,
            'flight_time'  => $flightTime,
        ]);
        $this->assertDatabaseHas('aircraft_maintenance', [
            'id'             => $this->maintenance->first()->id,
            'dom'            => $date->toDateString(),
            'maintenance_at' => $flightTime,
            'notify_at'      => null,
        ]);
    }

    /**
     * Test [PUT] aircraft/:aircraftRegistration/maintenance/:maintenanceID/complete resource.
     *
     * @covers \App\Http\Controllers\AircraftMaintenanceController
     * @return void
     */
    public function testCompleteRepetitionInterval()
    {
        $date = Carbon::now()->subDay()->toDateString();

        // Sign in as admin
        $this->actingAs($this->admin);

        // Success - flight_time similar new maintenance_at && notify_at
        $mt = AircraftMaintenance::factory()->for(Aircraft::factory())->notMaintained()->create();
        $resource = self::API_URL . 'aircraft/' . $mt->aircraft->registration . '/maintenance/' . $mt->id . '/complete';
        $notifyDiff = $mt->maintenance_at - $mt->notify_at;

        $response = $this->putJson($resource, [
            'dom'            => $date,
            'flight_time'    => $mt->aircraft->flight_time + 5,
            'maintenance_at' => $mt->aircraft->flight_time
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseHas('aircraft_maintenance', [
            'maintenance_at' => $mt->aircraft->flight_time + $mt->repetition_interval,
            'notify_at'      => $mt->aircraft->flight_time + $mt->repetition_interval - $notifyDiff,
        ]);

        // Success - flight_time much greater than new maintenance_at && notify_at
        $mt = AircraftMaintenance::factory()->for(Aircraft::factory())->notMaintained()->create();
        $resource = self::API_URL . 'aircraft/' . $mt->aircraft->registration . '/maintenance/' . $mt->id . '/complete';
        $notifyDiff = $mt->maintenance_at - $mt->notify_at;

        $response = $this->putJson($resource, [
            'dom'            => $date,
            'flight_time'    => $mt->aircraft->flight_time + $mt->repetition_interval + 5,
            'maintenance_at' => $mt->aircraft->flight_time
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseHas('aircraft_maintenance', [
            'maintenance_at' => $mt->aircraft->flight_time + (2 * $mt->repetition_interval) + 5,
            'notify_at'      => $mt->aircraft->flight_time + (2 * $mt->repetition_interval) + 5 - $notifyDiff,
        ]);

        // Success - flight_time similar new maintenance_at && no notify_at
        $mt = AircraftMaintenance::factory()->for(Aircraft::factory())->noNotification()->notMaintained()->create();
        $resource = self::API_URL . 'aircraft/' . $mt->aircraft->registration . '/maintenance/' . $mt->id . '/complete';

        $response = $this->putJson($resource, [
            'dom'            => $date,
            'flight_time'    => $mt->aircraft->flight_time + 5,
            'maintenance_at' => $mt->aircraft->flight_time
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseHas('aircraft_maintenance', [
            'maintenance_at' => $mt->aircraft->flight_time + $mt->repetition_interval,
            'notify_at'      => null,
        ]);

        // Success - flight_time much greater than new maintenance_at && no notify_at
        $mt = AircraftMaintenance::factory()->for(Aircraft::factory())->noNotification()->notMaintained()->create();
        $resource = self::API_URL . 'aircraft/' . $mt->aircraft->registration . '/maintenance/' . $mt->id . '/complete';

        $response = $this->putJson($resource, [
            'dom'            => $date,
            'flight_time'    => $mt->aircraft->flight_time + $mt->repetition_interval + 5,
            'maintenance_at' => $mt->aircraft->flight_time
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseHas('aircraft_maintenance', [
            'maintenance_at' => $mt->aircraft->flight_time + (2 * $mt->repetition_interval) + 5,
            'notify_at'      => null,
        ]);
    }

    /**
     * Test [POST] aircraft/:aircraftRegistration/maintenance resource.
     *
     * @covers \App\Http\Controllers\AircraftMaintenanceController
     * @return void
     */
    public function testCreate()
    {
        $flightTime = $this->maintenance->first()->aircraft->flight_time;
        $json = [
            'maintenance_at'      => $flightTime + 5000,
            'name'                => $this->faker->word,
            'notify_at'           => $flightTime + 5000 - 60,
            'repetition_interval' => 60,
        ];
        $invalidMaintenance = [
            'maintenance_at'      => $flightTime,
            'notify_at'           => 0,
            'repetition_interval' => 10,
        ];
        $invalidCompletedMaintenance = [
            'dom'                 => Carbon::now()->subDay(),
            'maintenance_at'      => $flightTime + 100,
            'notify_at'           => 5940,
            'repetition_interval' => 10,
        ];

        // Unauthorized
        $this->checkUnauthorized($this->resource, 'post');

        // Forbidden
        $this->checkForbidden($this->resource, 'post');

        // Sign in as admin
        $this->actingAs($this->admin);

        // Not found - aircraft
        $this->checkNotFound($this->aircraftNotFoundResource, 'post', $json);

        // Invalid input - without dom
        $this->checkInvalidInput($this->resource, 'post', $invalidMaintenance, [
            'maintenance_at'      => ['The Maintenance at must be greater than ' . $flightTime . '.'],
            'notify_at'           => ['The Notify at must be greater than ' . $flightTime . '.'],
            'repetition_interval' => ['The Repetition interval must be at least 60.'],
        ]);

        // Invalid input - with dom
        $this->checkInvalidInput($this->resource, 'post', $invalidCompletedMaintenance, [
            'maintenance_at'      => ['The Maintenance at must be between 0 and ' . $flightTime  . '.'],
            'notify_at'           => ['The Notify at field must not be present.'],
            'repetition_interval' => ['The Repetition interval field must not be present.'],
        ]);

        // Success
        $response = $this->postJson($this->resource, $json);
        $response->assertStatus(201)->assertJson(['message' => 'The maintenance has been created successfully.']);

        // Check database
        $this->assertDatabaseHas('aircraft_maintenance', $json);
    }

    /**
     * Test [DELETE] aircraft/:aircraftRegistration/maintenance/:maintenanceID resource.
     *
     * @covers \App\Http\Controllers\AircraftMaintenanceController
     * @return void
     */
    public function testDelete()
    {
        $resource = $this->resource . '/' . $this->maintenance->first()->id;

        // Unauthorized
        $this->checkUnauthorized($resource, 'delete');

        // Forbidden
        $this->checkForbidden($resource, 'delete');

        // Sign in as admin
        $this->actingAs($this->admin);

        // Not found - aircraft
        $this->checkNotFound(
            $this->aircraftNotFoundResource . '/' . $this->maintenance->first()->id,
            'delete'
        );

        // Not found - maintenance
        $this->checkNotFound($this->resource . '/9999', 'delete');

        // Not found - maintenance of different aircraft
        $maintenance = AircraftMaintenance::factory()->for(Aircraft::factory())->create();
        $this->checkNotFound($this->resource . '/' . $maintenance->id, 'delete');

        // Success
        $response = $this->deleteJson($resource);
        $response->assertStatus(200)->assertJson(['message' => 'The maintenance has been deleted successfully.']);
        $this->assertDeleted($this->maintenance->first());
    }

    /**
     * Test [DELETE] aircraft/:aircraftRegistration/maintenance resource.
     *
     * @covers \App\Http\Controllers\AircraftMaintenanceController
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

        // Not found - aircraft
        $this->checkNotFound($this->aircraftNotFoundResource, 'delete', ['ids' => ['1']]);

        // Invalid input
        $this->checkInvalidInput($this->resource, 'delete', [], [
            'ids' => ["The ids field is required."]
        ]);

        // Success
        $response = $this->deleteJson($this->resource, ['ids' => $this->maintenance->pluck('id')->toArray()]);
        $response->assertStatus(200)->assertJson(['message' => '3 maintenance have been deleted successfully.']);
        foreach ($this->maintenance as $maintenance) {
            $this->assertDeleted($maintenance);
        }

        // Success - Not delete maintenance of different aircraft
        $maintenance = AircraftMaintenance::factory()->for(Aircraft::factory())->create();
        $response = $this->deleteJson($this->resource, ['ids' => [$maintenance->id]]);
        $response->assertStatus(200)->assertJson(['message' => 'No maintenance have been deleted.']);
    }

    /**
     * Test [GET] aircraft/:aircraftRegistration/maintenance/:maintenanceID resource.
     *
     * @covers \App\Http\Controllers\AircraftMaintenanceController
     * @return void
     */
    public function testGet()
    {
        $resource = $this->resource . '/' . $this->maintenance->first()->id;

        // Unauthorized
        $this->checkUnauthorized($resource);

        // Forbidden
        $this->checkForbidden($resource);

        // Sign in as admin
        $this->actingAs($this->admin);

        // Not found - aircraft
        $this->checkNotFound($this->aircraftNotFoundResource . '/' . $this->maintenance->first()->id);

        // Not found - maintenance
        $this->checkNotFound($this->resource . '/9999');

        // Not found - maintenance of different aircraft
        $maintenance = AircraftMaintenance::factory()->for(Aircraft::factory())->create();
        $this->checkNotFound($this->resource . '/' . $maintenance->id);

        // Success
        $response = $this->getJson($resource);
        $response->assertStatus(200)
            ->assertJson(AircraftMaintenance::find($this->maintenance->first()->id)->toArray());
    }

    /**
     * Test [PUT] aircraft/:aircraftRegistration/maintenance/:maintenanceID resource.
     *
     * @covers \App\Http\Controllers\AircraftMaintenanceController
     * @return void
     */
    public function testUpdate()
    {
        $resource = $this->resource . '/' . $this->maintenance->first()->id;

        // Unauthorized
        $this->checkUnauthorized($resource, 'put');

        // Forbidden
        $this->checkForbidden($resource, 'put');

        // Sign in as admin
        $this->actingAs($this->admin);

        // Bad Request - Already completed
        $maintenance = AircraftMaintenance::factory()->for(Aircraft::factory())->maintained()->create();
        $badRequestResource = self::API_URL . 'aircraft/' . $maintenance->aircraft->registration . '/maintenance/'
            . $maintenance->id;
        $this->checkBadRequest(
            $badRequestResource,
            'The maintenance of the aircraft has been completed, you cannot update this dataset.',
            'put'
        );

        // Not found - aircraft
        $this->checkNotFound(
            $this->aircraftNotFoundResource . '/' . $this->maintenance->first()->id,
            'put'
        );

        // Not found - maintenance
        $this->checkNotFound($this->resource . '/9999', 'put');

        // Not found - maintenance of different aircraft
        $maintenance = AircraftMaintenance::factory()->for(Aircraft::factory())->create();
        $this->checkNotFound($this->resource . '/' . $maintenance->id, 'put');

        // Invalid input
        $flightTime = $this->maintenance->first()->aircraft->flight_time;
        $this->checkInvalidInput($resource, 'put', [
            'maintenance_at'      => 5000000000,
            'notify_at'           => $flightTime,
            'repetition_interval' => 'invalid',
        ], [
            'maintenance_at'      => ['The Maintenance at may not be greater than 4294967295.'],
            'notify_at'           => ['The Notify at must be greater than ' . $flightTime . '.'],
            'repetition_interval' => [
                'The Repetition interval must be an integer.',
                'The Repetition interval must be at least 60.',
            ],
        ]);

        // Success
        $maintenanceAt = $this->maintenance->first()->aircraft->flight_time + 100;
        $notifyAt = $maintenanceAt - 20;
        $response = $this->putJson($resource, ['maintenance_at' => $maintenanceAt, 'notify_at' => $notifyAt]);
        $response->assertStatus(200)
            ->assertJsonFragment(['maintenance_at' => $maintenanceAt, 'notify_at' => $notifyAt]);

        // Check database
        $this->assertDatabaseHas('aircraft_maintenance', [
            'id' => $this->maintenance->first()->id,
            'maintenance_at' => $maintenanceAt,
            'notify_at' => $notifyAt,
        ]);
    }
}
