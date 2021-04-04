<?php

namespace Tests\Feature\AircraftLogbookItem;

use App\Models\Aircraft;
use App\Models\AircraftLogbook;
use App\Models\AircraftLogbookItem;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Tests\TestCase;

/**
 * Class AircraftLogbookItemResourcesTest
 * @package Tests\Feature\AircraftLogbookItem
 */
class AircraftLogbookItemResourcesTest extends TestCase
{
    use WithFaker;

    /**
     * @var AircraftLogbookItem|\Illuminate\Database\Eloquent\Collection
     */
    protected $entries;

    /**
     * @var string
     */
    protected $resource;

    /**
     * @var string
     */
    protected $aircraftNotFoundResource = self::API_URL . 'aircraft/X-XXXX/logbook/entries';

    public function setUp(): void
    {
        parent::setUp();

        // Create logbook entries
        $this->entries = AircraftLogbookItem::factory()
            ->count(3)
            ->for(AircraftLogbook::factory()->for(Aircraft::factory()), 'logbook')
            ->create();
        $registration = $this->entries->first()->logbook->aircraft->registration;

        $this->resource = self::API_URL . 'aircraft/' . $registration . '/logbook/entries';
    }

    /**
     * Test [GET] aircraft/:aircraftRegistration/logbook/entries resource.
     *
     * @covers \App\Http\Controllers\AircraftLogbookItemController
     * @return void
     */
    public function testAll(): void
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
            ->assertJsonFragment(['message' => 'Requested filter(s) `invalid` are not allowed. Allowed filter(s) are `arrival, arrival_at_after, arrival_at_before, block_time, block_time_elt, block_time_emt, crew, crew_elt, crew_emt, departure, departure_at_after, departure_at_before, destination, id, origin, pax, pax_elt, pax_emt, pilot_firstname, pilot_id, pilot_lastname`.']);

        // Invalid sorting
        $response = $this->getJson($this->resource . '?sort=invalid');
        $response->assertStatus(400)
            ->assertJsonFragment(['message' => 'Requested sort(s) `invalid` is not allowed. Allowed sort(s) are `arrival, block_time, crew, departure, destination, id, origin, pax, pilot_firstname, pilot_id, pilot_lastname`.']);

        // Success
        $response = $this->getJson($this->resource);
        $response->assertStatus(200)
            ->assertJsonFragment(['per_page' => 50])
            ->assertJsonFragment(['total' => AircraftLogbookItem::all()->count()]);

        // Filtering
        $response = $this->getJson($this->resource . '?filter[block_time]=' . $this->entries->first()->block_time);
        $count = AircraftLogbookItem::where('block_time', '=', $this->entries->first()->block_time)
            ->count();
        $response->assertStatus(200)->assertJsonFragment(['total' => $count]);
    }

    /**
     * Test [POST] aircraft/:aircraftRegistration/logbook/entries resource.
     *
     * @covers \App\Http\Controllers\AircraftLogbookItemController
     * @return void
     */
    public function testCreate(): void
    {
        $aircraft = $this->entries->first()->logbook->aircraft;

        $json = [
            'arrival'     => Carbon::parse(
                $this->faker->dateTimeBetween('-45 minutes', 'now', 'UTC')
            )->toDateTimeString(),
            'crew'        => 1,
            'departure'   => Carbon::parse(
                $this->faker->dateTimeBetween('-90 minutes', '-1 hour', 'UTC')
            )->toDateTimeString(),
            'destination' => 'EQDN',
            'notes'       => 'Nothing special.',
            'origin'      => 'EQDN',
            'pax'         => $this->faker->numberBetween(1, $aircraft->seats),
            'pilot_id'    => $this->admin->id,
        ];
        $invalidEntry = [
            'arrival'         => Carbon::parse(
                $this->faker->dateTimeBetween('-90 minutes', '-60 minutes', 'UTC')
            )->toDateTimeString(),
            'crew'            => 0,
            'departure'       => Carbon::parse(
                $this->faker->dateTimeBetween('-30 minutes', '-10 minutes', 'UTC')
            )->toDateTimeString(),
            'destination'     => 'EQDN',
            'origin'          => 'XXXX',
            'pax'             => $aircraft->seats + 1,
            'pilot_id'        => 9999,
            'pilot_signature' => 'invalid',
        ];

        // Unauthorized
        $this->checkUnauthorized($this->resource, 'post');

        // Forbidden
        $this->checkForbidden($this->resource, 'post');

        // Sign in as admin
        $this->actingAs($this->admin);

        // Not found - aircraft
        $this->checkNotFound($this->aircraftNotFoundResource, 'post');

        // Invalid input
        $this->checkInvalidInput($this->resource, 'post', $invalidEntry, [
            'arrival'         => ['The Arrival must be a date after Departure.'],
            'crew'            => ['The Crew must be at least 1.'],
            'departure'       => ['The Departure must be a date before Arrival.'],
            'origin'          => ['The Origin must be EQDN.'],
            'pax'             => ["The Passengers may not be greater than {$aircraft->seats}."],
            'pilot_id'        => ['The selected Pilot is invalid.'],
            'pilot_signature' => ['The Pilot signature must start with one of the following: data:image/png;base64.'],
        ]);

        // Success
        $response = $this->postJson($this->resource, $json);
        $response->assertStatus(201)
            ->assertJson(['message' => 'The entry of the aircraft logbook has been created successfully.']);

        // Check database
        $json = \array_merge($json, [
            'block_time'      => Carbon::parse($json['departure'])->diffInMinutes($json['arrival']),
            'pilot_firstname' => $this->admin->firstname,
            'pilot_lastname'  => $this->admin->lastname,
        ]);
        $this->assertDatabaseHas('aircraft_logbook_items', $json);
    }

    /**
     * Test [DELETE] aircraft/:aircraftRegistration/logbook/entries/:logbookItemId resource.
     *
     * @covers \App\Http\Controllers\AircraftLogbookItemController
     * @return void
     */
    public function testDelete(): void
    {
        $resource = $this->resource . '/' . $this->entries->first()->id;

        // Unauthorized
        $this->checkUnauthorized($resource, 'delete');

        // Forbidden
        $this->checkForbidden($resource, 'delete');

        // Sign in as admin
        $this->actingAs($this->admin);

        // Not found - aircraft
        $this->checkNotFound(
            $this->aircraftNotFoundResource . '/' . $this->entries->first()->id,
            'delete'
        );

        // Not found - entry
        $this->checkNotFound($this->resource . '/9999', 'delete');

        // Not found - entry of different logbook
        $entry = AircraftLogbookItem::factory()
            ->for(AircraftLogbook::factory()->for(Aircraft::factory()), 'logbook')
            ->create();
        $this->checkNotFound($this->resource . '/' . $entry->id, 'delete');

        // Success
        $response = $this->deleteJson($resource);
        $response->assertStatus(200)
            ->assertJson(['message' => 'The logbook entry has been deleted successfully.']);
        $this->assertDeleted($this->entries->first());
    }

    /**
     * Test [DELETE] aircraft/:aircraftRegistration/logbook/entries resource.
     *
     * @covers \App\Http\Controllers\AircraftLogbookItemController
     * @return void
     */
    public function testDeleteBulk(): void
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
        $response = $this->deleteJson($this->resource, ['ids' => $this->entries->pluck('id')->toArray()]);
        $response->assertStatus(200)
            ->assertJson(['message' => '3 logbook entries have been deleted successfully.']);
        foreach ($this->entries as $entry) {
            $this->assertDeleted($entry);
        }

        // Success - Not delete entry of different logbook
        $entry = AircraftLogbookItem::factory()
            ->for(AircraftLogbook::factory()->for(Aircraft::factory()), 'logbook')
            ->create();
        $response = $this->deleteJson($this->resource, ['ids' => [$entry->id]]);
        $response->assertStatus(200)->assertJson(['message' => 'No logbook entries have been deleted.']);
    }

    /**
     * Test [GET] aircraft/:aircraftRegistration/logbook/entries/:logbookItemId resource.
     *
     * @covers \App\Http\Controllers\AircraftLogbookItemController
     * @return void
     */
    public function testGet(): void
    {
        $resource = $this->resource . '/' . $this->entries->first()->id;

        // Unauthorized
        $this->checkUnauthorized($resource);

        // Forbidden
        $this->checkForbidden($resource);

        // Sign in as admin
        $this->actingAs($this->admin);

        // Not found - aircraft
        $this->checkNotFound($this->aircraftNotFoundResource . '/' . $this->entries->first()->id);

        // Not found - logbook entry
        $this->checkNotFound($this->resource . '/9999');

        // Not found - entry of different logbook
        $entry = AircraftLogbookItem::factory()
            ->for(AircraftLogbook::factory()->for(Aircraft::factory()), 'logbook')
            ->create();
        $this->checkNotFound($this->resource . '/' . $entry->id);

        // Success
        $response = $this->getJson($resource);
        $response->assertStatus(200)
            ->assertJson(AircraftLogbookItem::find($this->entries->first()->id)->toArray());
    }

    /**
     * Test [PUT] aircraft/:aircraftRegistration/logbook/entries/:logbookItemId resource.
     *
     * @covers \App\Http\Controllers\AircraftLogbookItemController
     * @return void
     */
    public function testUpdate(): void
    {
        $resource = $this->resource . '/' . $this->entries->first()->id;

        // Unauthorized
        $this->checkUnauthorized($resource, 'put');

        // Forbidden
        $this->checkForbidden($resource, 'put');

        // Sign in as admin
        $this->actingAs($this->admin);

        // Not found - aircraft
        $this->checkNotFound($this->aircraftNotFoundResource . '/' . $this->entries->first()->id, 'put');

        // Not found - entry
        $this->checkNotFound($this->resource . '/9999', 'put');

        // Not found - entry of different logbook
        $entry = AircraftLogbookItem::factory()
            ->for(AircraftLogbook::factory()->for(Aircraft::factory()), 'logbook')
            ->create();
        $this->checkNotFound($this->resource . '/' . $entry->id, 'put');

        // Invalid input
        $entry = $this->entries->first();
        $seats = $entry->logbook->aircraft->seats;

        $this->checkInvalidInput($resource, 'put', [
            'arrival'         => $entry->departure->subMinute()->toDateTimeString(),
            'crew'            => 0,
            'pax'             => $seats + 5,
            'pilot_id'        => 9999,
            'pilot_signature' => 'invalid',
        ], [
            'arrival'         => ['The Arrival must be a date after Departure.'],
            'crew'            => ['The Crew must be at least 1.'],
            'departure'       => ['The Departure must be a date before Arrival.'],
            'pax'             => ['The Passengers may not be greater than ' . $seats . '.'],
            'pilot_id'        => ['The selected Pilot is invalid.'],
            'pilot_signature' => ['The Pilot signature must start with one of the following: data:image/png;base64.'],
        ]);

        // Success
        $arrival = $this->entries->first()->arrival->subMinutes(5)->toDateTimeString();
        $newBlockTime = $this->entries->first()->block_time - 5;

        $response = $this->putJson($resource, ['arrival' => $arrival, 'pax' => 0, 'pilot_id' => $this->admin->id]);
        $response->assertStatus(200)->assertJsonFragment([
            'arrival'    => $arrival,
            'block_time' => $newBlockTime,
            'pax'        => 0,
            'pilot_id'   => $this->admin->id
        ]);

        // Check database
        $this->assertDatabaseHas('aircraft_logbook_items', [
            'id'              => $this->entries->first()->id,
            'arrival'         => $arrival,
            'block_time'      => $newBlockTime,
            'pax'             => 0,
            'pilot_firstname' => $this->admin->firstname,
            'pilot_id'        => $this->admin->id,
            'pilot_lastname'  => $this->admin->lastname,
        ]);

        // Success - Set pilot_id null and check database
        $response = $this->putJson($resource, ['pilot_id' => '']);
        $response->assertStatus(200)->assertJsonFragment(['pilot_id' => null]);

        $this->assertDatabaseHas('aircraft_logbook_items', [
            'pilot_firstname' => $this->admin->firstname,
            'pilot_id'        => null,
            'pilot_lastname'  => $this->admin->lastname,
        ]);
    }
}
