<?php

namespace Tests\Feature\User;

use App\Models\User;
use App\Models\Waiver;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

class UserWaiverResourcesTest extends TestCase
{
    use WithFaker;

    /**
     * @var string
     */
    protected $resource = self::API_URL . 'users';

    /**
     * @var \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|User
     */
    protected $user;

    /**
     * @var string
     */
    protected $userNotFoundResource = self::API_URL . 'users/9999/waivers';

    /**
     * @var \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|Waiver[]
     */
    protected $waivers;

    public function setUp(): void
    {
        parent::setUp();

        // Create user and waivers
        $this->user = (User::factory()->count(1)->isUser()->create())->first();
        $this->waivers = Waiver::factory()->count(3)->hasTexts(5)->create();

        // Attach signed waivers to user
        $this->user->waivers()->attach($this->waivers->pluck('id')->toArray(), ['signature' => 'test']);

        $this->resource = self::API_URL . 'users/' . $this->user->id . '/waivers';
    }

    /**
     * Test [GET] users/:userID/waivers resource.
     *
     * @covers \App\Http\Controllers\UserWaiverController
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

        // Not found - user
        $this->checkNotFound($this->userNotFoundResource);

        // Invalid filtering
        $response = $this->getJson($this->resource . '?filter[invalid]=somevalue');
        $response->assertStatus(400)
            ->assertJsonFragment(['message' => 'Requested filter(s) `invalid` are not allowed. Allowed filter(s) are `id, title`.']);

        // Invalid sorting
        $response = $this->getJson($this->resource . '?sort=invalid');
        $response->assertStatus(400)
            ->assertJsonFragment(['message' => 'Requested sort(s) `invalid` is not allowed. Allowed sort(s) are `id, title`.']);

        // Success
        $response = $this->getJson($this->resource);
        $response->assertStatus(200)
            ->assertJsonFragment(['per_page' => 50])
            ->assertJsonFragment(['total' => $this->user->waivers()->count()]);

        // Filtering
        $response = $this->getJson($this->resource . '?filter[title]=' . $this->waivers->first()->title);
        $count = Waiver::where('title', 'like', '%' . $this->waivers->first()->title . '%')
            ->count();
        $response
            ->assertStatus(200)
            ->assertJsonFragment(['total' => $count]);
    }

    /**
     * Test [DELETE] users/:userID/waivers/:waiverID resource.
     *
     * @covers \App\Http\Controllers\UserWaiverController
     * @return void
     */
    public function testDelete()
    {
        $resource = $this->resource . '/' . $this->waivers->first()->id;

        // Unauthorized
        $this->checkUnauthorized($resource, 'delete');

        // Forbidden
        $this->checkForbidden($resource, 'delete');

        // Sign in as admin
        $this->actingAs($this->admin);

        // Not found - user
        $this->checkNotFound($this->userNotFoundResource, 'delete');

        // Not found - waiver
        $this->checkNotFound($this->resource . '/9999', 'delete');

        // Success
        $response = $this->deleteJson($resource);
        $response->assertStatus(200)
            ->assertJson(['message' => 'The signed waiver has been deleted successfully.']);

        // Check database
        $this->assertDatabaseMissing(
            'user_waiver',
            ['user_id' => $this->user->id, 'waiver_id' => $this->waivers->first()->id, ]
        );
    }

    /**
     * Test [DELETE] users/:userID/waivers resource.
     *
     * @covers \App\Http\Controllers\UserWaiverController
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

        // Not found - user
        $this->checkNotFound($this->userNotFoundResource, 'delete');

        // Invalid input
        $this->checkInvalidInput($this->resource, 'delete', [], [
            'ids' => ["The ids field is required."]
        ]);

        // Cannot delete signed waiver of different user
        $user = (User::factory()->count(1)->isUser()->create())->first();
        $waiver = (Waiver::factory()->count(1)->create())->first();
        $user->waivers()->attach($waiver->id, ['signature' => 'test']);
        $response = $this->deleteJson($this->resource, ['ids' => [$waiver->id]]);
        $response->assertStatus(200)->assertJson(['message' => 'No signed waivers have been deleted.']);

        // Success
        $response = $this->deleteJson($this->resource, ['ids' => $this->waivers->pluck('id')->toArray()]);
        $response->assertStatus(200)
            ->assertJson(['message' => '3 signed waivers have been deleted successfully.']);
        $this->assertEquals(0, $this->user->waivers->count());
    }

    /**
     * Test [GET] users/:userID/waivers/:waiverID resource.
     *
     * @covers \App\Http\Controllers\UserWaiverController
     * @return void
     */
    public function testGet()
    {
        $resource = $this->resource . '/' . $this->waivers->first()->id;

        // Unauthorized
        $this->checkUnauthorized($resource);

        // Forbidden
        $this->checkForbidden($resource);

        // Sign in as admin
        $this->actingAs($this->admin);

        // Not found - user
        $this->checkNotFound($this->userNotFoundResource);

        // Not found - waiver
        $this->checkNotFound($this->resource . '/9999', 'delete');

        // Success
        $signature = $this->user
            ->waivers()
            ->withPivot(['signature'])
            ->find($this->waivers->first())
            ->first()
            ->load(['texts' => function ($q) {
                $q->whereLanguageCode(App::getLocale())->orderBy('position');
            }])
            ->toArray();
        $response = $this->getJson($resource);
        $response->assertStatus(200)->assertJson($signature);
    }
}
