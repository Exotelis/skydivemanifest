<?php

namespace Tests\Feature\AircraftMaintenance;

use App\Models\Aircraft;
use App\Models\AircraftLogbook;
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
        $aircraftDom = $this->maintenance->first()->aircraft->dom ?? Carbon::minValue()->toDateString();
        $this->checkInvalidInput($resource, 'put', [
            'dom'         => 'invalid',
        ], [
            'dom'            => [
                'The Maintenance date is not a valid date.',
                "The Maintenance date must be a date after or equal to $aircraftDom."
            ],
            'maintenance_at' => ['The Maintenance at field is required.'],
        ]);

        // Success
        $date = Carbon::now()->subDay();
        $maintenanceAt = $this->maintenance->first()->aircraft->operation_time;
        $response = $this->putJson($resource, [
            'dom'            => $date,
            'maintenance_at' => $maintenanceAt,
        ]);
        $response->assertStatus(200)
            ->assertJsonFragment(['dom' => $date->toDateString(), 'maintenance_at' => $maintenanceAt]);

        // Check database
        $this->assertDatabaseHas('aircraft_maintenance', [
            'id'             => $this->maintenance->first()->id,
            'dom'            => $date->toDateString(),
            'maintenance_at' => $maintenanceAt,
            'notify_at'      => null,
        ]);
    }

    /**
     * Test [PUT] aircraft/:aircraftRegistration/maintenance/:maintenanceID/complete resource.
     *
     * @covers \App\Http\Controllers\AircraftMaintenanceController
     * @return void
     */
    public function testCompleteWithRepetitionInterval()
    {
        $date = Carbon::now()->subDay()->toDateString();

        // Sign in as admin
        $this->actingAs($this->admin);

        // Success - maintenance_at is the current operation_time && notify_at is not null
        $mt = AircraftMaintenance::factory()
            ->for(Aircraft::factory()->has(AircraftLogbook::factory()->highTransfer(), 'logbook'))
            ->notMaintained()
            ->repetitive()
            ->create();
        $resource = self::API_URL . 'aircraft/' . $mt->aircraft->registration . '/maintenance/' . $mt->id . '/complete';
        $notifyDiff = $mt->maintenance_at - $mt->notify_at;

        $response = $this->putJson($resource, [
            'dom'            => $date,
            'maintenance_at' => $mt->aircraft->operation_time,
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseHas('aircraft_maintenance', [
            'maintenance_at' => $mt->aircraft->operation_time + $mt->repetition_interval,
            'notify_at'      => $mt->aircraft->operation_time + $mt->repetition_interval - $notifyDiff,
        ]);

        // Success - operation_time much greater than maintenance_at && notify_at is not null
        $mt = AircraftMaintenance::factory()
            ->for(Aircraft::factory()->has(AircraftLogbook::factory()->highTransfer(), 'logbook'))
            ->notMaintained()
            ->repetitive()
            ->create();
        $resource = self::API_URL . 'aircraft/' . $mt->aircraft->registration . '/maintenance/' . $mt->id . '/complete';
        $notifyDiff = $mt->maintenance_at - $mt->notify_at;

        $response = $this->putJson($resource, [
            'dom'            => $date,
            'maintenance_at' => $mt->aircraft->operation_time - $mt->repetition_interval - 100,
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseHas('aircraft_maintenance', [
            'maintenance_at' => $mt->aircraft->operation_time - 100,
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
        $operationTime = $this->maintenance->first()->aircraft->operation_time;
        $json = [
            'maintenance_at'      => $operationTime + 5000,
            'name'                => $this->faker->word,
            'notify_at'           => $operationTime + 5000 - 60,
            'repetition_interval' => 60,
        ];
        $jsonDom = [
            'dom'                 => Carbon::now()->toDateString(),
            'maintenance_at'      => 0,
            'name'                => $this->faker->word,
            'notes'               => 'First maintenance'
        ];
        $invalidMaintenance = [
            'maintenance_at'      => $operationTime,
            'notify_at'           => 0,
            'repetition_interval' => 10,
        ];
        $invalidCompletedMaintenance = [
            'dom'                 => Carbon::now()->subDay(),
            'maintenance_at'      => $operationTime + 100,
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
        $this->checkNotFound($this->aircraftNotFoundResource, 'post');

        // Invalid input - without dom
        $this->checkInvalidInput($this->resource, 'post', $invalidMaintenance, [
            'maintenance_at'      => ['The Maintenance at must be greater than ' . $operationTime . '.'],
            'notify_at'           => ['The Notify at must be greater than ' . $operationTime . '.'],
            'repetition_interval' => ['The Repetition interval must be at least 60.'],
        ]);

        // Invalid input - with dom
        $this->checkInvalidInput($this->resource, 'post', $invalidCompletedMaintenance, [
            'maintenance_at'      => ['The Maintenance at must be between 0 and ' . $operationTime  . '.'],
            'notify_at'           => ['The Notify at field must not be present.'],
            'repetition_interval' => ['The Repetition interval field must not be present.'],
        ]);

        // Success
        $response = $this->postJson($this->resource, $json);
        $response->assertStatus(201)->assertJson(['message' => 'The maintenance has been created successfully.']);
        $response = $this->postJson($this->resource, $jsonDom);
        $response->assertStatus(201)->assertJson(['message' => 'The maintenance has been created successfully.']);

        // Check database
        $this->assertDatabaseHas('aircraft_maintenance', $json);
        $this->assertDatabaseHas('aircraft_maintenance', $jsonDom);
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
        $this->checkNotFound($this->aircraftNotFoundResource, 'delete');

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
        $operationTime = $this->maintenance->first()->aircraft->operation_time;
        $this->checkInvalidInput($resource, 'put', [
            'maintenance_at'      => 5000000000,
            'notify_at'           => $operationTime,
            'repetition_interval' => 'invalid',
        ], [
            'maintenance_at'      => ['The Maintenance at may not be greater than 4294967295.'],
            'notify_at'           => ['The Notify at must be greater than ' . $operationTime . '.'],
            'repetition_interval' => [
                'The Repetition interval must be an integer.',
                'The Repetition interval must be at least 60.',
            ],
        ]);

        // Success
        $maintenanceAt = $this->maintenance->first()->aircraft->operation_time + 100;
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
