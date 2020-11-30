<?php

namespace Tests\Feature\UnassignedWaiver;

use App\Models\UnassignedWaiver;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * Class UnassignedWaiverResourcesTest
 * @package Tests\Feature\UnassignedWaiver
 */
class UnassignedWaiverResourcesTest extends TestCase
{
    use WithFaker;

    /**
     * @var string
     */
    protected $resource = self::API_URL . 'unassigned-waivers';

    /**
     * @var \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|UnassignedWaiver[]
     */
    protected $unassignedWaivers;

    public function setUp(): void
    {
        parent::setUp();

        // Create unassigned waivers
        $this->unassignedWaivers = UnassignedWaiver::factory()->count(3)->forWaiver()->create();
    }

    /**
     * Test [GET] unassigned-waivers resource.
     *
     * @covers \App\Http\Controllers\UnassignedWaiverController
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
            ->assertJsonFragment(['message' => 'Requested filter(s) `invalid` are not allowed. Allowed filter(s) are `email, firstname, id, ip, lastname`.']);

        // Invalid sorting
        $response = $this->getJson($this->resource . '?sort=invalid');
        $response->assertStatus(400)
            ->assertJsonFragment(['message' => 'Requested sort(s) `invalid` is not allowed. Allowed sort(s) are `email, firstname, id, ip, lastname`.']);

        // Success
        $response = $this->getJson($this->resource);
        $response->assertStatus(200)
            ->assertJsonFragment(['per_page' => 50])
            ->assertJsonFragment(['total' => $this->unassignedWaivers->count()]);

        // Filtering
        $response = $this->getJson(
            $this->resource . '?filter[firstname]=' . $this->unassignedWaivers->first()->firstname
        );
        $count = UnassignedWaiver::where(
            'firstname', 'like', '%' . $this->unassignedWaivers->first()->firstname . '%'
        )->count();
        $response->assertStatus(200)
            ->assertJsonFragment(['total' => $count]);
    }

    /**
     * Test [Post] unassigned-waivers/:unassignedWaiverID/assign resource.
     *
     * @covers \App\Http\Controllers\UnassignedWaiverController
     * @return void
     */
    public function testAssign()
    {
        $resource = $this->resource . '/' . $this->unassignedWaivers->first()->id . '/assign';

        // Unauthorized
        $this->checkUnauthorized($resource, 'post');

        // Forbidden
        $this->checkForbidden($resource, 'post');

        // Sign in as admin
        $this->actingAs($this->admin);

        // Not found
        $this->checkNotFound($this->resource . '/9999' . '/assign', 'post');

        // Invalid input
        $this->checkInvalidInput($resource, 'post', ['user_id' => 9999], [
            'user_id' => ['The selected User ID is invalid.']
        ]);

        // Success
        $user = User::factory()->isActive()->isVerified()->noPasswordChange()->create();
        $response = $this->postJson($resource, ['user_id' => $user->id]);
        $response->assertStatus(201)->assertJson(
            ['message' => "The waiver has been assigned successfully to user {$user->email} ({$user->id})."]
        );

        // Check database
        $this->assertDeleted($this->unassignedWaivers->first());
        $this->assertDatabaseHas(
            'user_waiver',
            ['user_id' => $user->id, 'waiver_id' => $this->unassignedWaivers->first()->id]
        );
    }

    /**
     * Test [DELETE] unassigned-waivers/:unassignedWaiverID resource.
     *
     * @covers \App\Http\Controllers\UnassignedWaiverController
     * @return void
     */
    public function testDelete()
    {
        $resource = $this->resource . '/' . $this->unassignedWaivers->first()->id;

        // Unauthorized
        $this->checkUnauthorized($resource, 'delete');

        // Forbidden
        $this->checkForbidden($resource, 'delete');

        // Sign in as admin
        $this->actingAs($this->admin);

        // Not found
        $this->checkNotFound($this->resource . '/9999', 'delete');

        // Success
        $response = $this->deleteJson($resource);
        $response->assertStatus(200)
            ->assertJson(['message' => 'The unassigned waiver has been deleted successfully.']);
        $this->assertDeleted($this->unassignedWaivers->first());
    }

    /**
     * Test [DELETE] unassigned-waivers resource.
     *
     * @covers \App\Http\Controllers\UnassignedWaiverController
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
        $response = $this->deleteJson(
            $this->resource,
            ['ids' => $this->unassignedWaivers->pluck('id')->toArray()]
        );
        $response->assertStatus(200)
            ->assertJson(['message' => '3 unassigned waivers have been deleted successfully.']);

        // Check database
        foreach ($this->unassignedWaivers as $unassignedWaivers) {
            $this->assertDeleted($unassignedWaivers);
        }
    }

    /**
     * Test [GET] unassigned-waivers/:unassignedWaiverID resource.
     *
     * @covers \App\Http\Controllers\UnassignedWaiverController
     * @return void
     */
    public function testGet()
    {
        $resource = $this->resource . '/' . $this->unassignedWaivers->first()->id;

        // Unauthorized
        $this->checkUnauthorized($resource);

        // Forbidden
        $this->checkForbidden($resource);

        // Sign in as admin
        $this->actingAs($this->admin);

        // Not found
        $this->checkNotFound($this->resource . '/9999');

        // Success
        $response = $this->getJson($resource);
        $response->assertStatus(200)
            ->assertJson(
                UnassignedWaiver::with('waiver')->find($this->unassignedWaivers->first()->id)->toArray()
            );
    }
}
