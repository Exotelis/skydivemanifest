<?php

namespace Tests\Feature\Waiver;

use App\Models\User;
use App\Models\Waiver;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * Class WaiverResourcesTest
 * @package Tests\Feature\Waiver
 */
class WaiverResourcesTest extends TestCase
{
    use WithFaker;

    /**
     * @var \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|Waiver[]
     */
    protected $waivers;

    /**
     * @var string
     */
    protected $resource = self::API_URL . 'waivers';

    public function setUp(): void
    {
        parent::setUp();

        // Create waivers
        $this->waivers = Waiver::factory()->count(3)->hasTexts(3)->create();
    }

    /**
     * Test [GET] /waivers/active resource.
     *
     * @covers \App\Http\Controllers\WaiverController
     * @return void
     */
    public function testActiveAll()
    {
        $resource = $this->resource . '/active';

        // Create at least one active waiver
        Waiver::factory()->isActive()->hasTexts(3)->create();

        // Unauthorized
        $this->checkUnauthorized($resource);

        // Sign in as admin without permissions
        $this->actingAs($this->admin, []);

        // Invalid filtering
        $response = $this->getJson($resource . '?filter[invalid]=somevalue');
        $response->assertStatus(400)
            ->assertJsonFragment(['message' => 'Requested filter(s) `invalid` are not allowed. Allowed filter(s) are `id, language_code, title`.']);

        // Invalid sorting
        $response = $this->getJson($resource . '?sort=invalid');
        $response->assertStatus(400)
            ->assertJsonFragment(['message' => 'Requested sort(s) `invalid` is not allowed. Allowed sort(s) are `id, title`.']);

        // Success
        $response = $this->getJson($resource);
        $response->assertStatus(200)
            ->assertJsonFragment(['per_page' => 50])
            ->assertJsonFragment(['total' => Waiver::whereIsActive(true)->get()->count()]);

        // Filtering
        $resourceWithFilter = $resource . '?filter[language_code]=en';
        $response = $this->getJson($resourceWithFilter);

        $count = Waiver::whereIsActive(true)->first()->texts()->whereLanguageCode('en')->count();
        $responseCount = null;

        foreach ($response['data'] as $waiver) {
            if (Waiver::whereIsActive(true)->first()->id === $waiver['id']) {
                $responseCount = \count($waiver['texts']);
                break;
            }
        }

        $this->assertEquals($count,$responseCount);
    }

    /**
     * Test [GET] /waivers/active/:waiverID resource.
     *
     * @covers \App\Http\Controllers\WaiverController
     * @return void
     */
    public function testActiveGet()
    {
        // Create an active waiver
        $waiver = Waiver::factory()->isActive()->hasTexts(3)->create();

        $resource = $this->resource . '/active/' . $waiver->id;

        // Unauthorized
        $this->checkUnauthorized($resource);

        // Sign in as admin without permissions
        $this->actingAs($this->admin, []);

        // Not found
        $this->checkNotFound($this->resource . '/active/9999');

        // Invalid filtering
        $response = $this->getJson($resource . '?filter[invalid]=somevalue');
        $response->assertStatus(400)
            ->assertJsonFragment(['message' => 'Requested filter(s) `invalid` are not allowed. Allowed filter(s) are `language_code`.']);

        // Success
        $response = $this->getJson($resource);
        $response->assertStatus(200)
            ->assertJson($waiver->toArray());

        // Filtering
        $resourceWithFilter = $resource . '?filter[language_code]=en';
        $response = $this->getJson($resourceWithFilter);

        $count = Waiver::whereIsActive(true)->find($waiver->id)->texts()->whereLanguageCode('en')->count();
        $responseCount = $response->getOriginalContent()->texts->count();

        $this->assertEquals($count,$responseCount);
    }

    /**
     * Test [POST] /waivers/active/:waiverID/sign resource.
     *
     * @covers \App\Http\Controllers\WaiverController
     * @return void
     */
    public function testActiveSign()
    {
        // Create an active waiver
        $waiver = Waiver::factory()->isActive()->hasTexts(3)->create();

        $resource = $this->resource . '/active/' . $waiver->id . '/sign';

        // Verify current state
        $this->assertDatabaseMissing('user_waiver', [
            'user_id'   => $this->admin->id,
            'waiver_id' => $waiver->id,
        ]);

        // Unauthorized
        $this->checkUnauthorized($resource, 'post');

        // Sign in as admin without permissions
        $this->actingAs($this->admin, []);

        // Not found
        $this->checkNotFound($this->resource . '/active/9999/sign', 'post');

        // Invalid input
        $this->checkInvalidInput($resource, 'post', [], [
            'signature' => ['The Signature field is required.'],
        ]);

        // Success
        $response = $this->postJson(
            $resource,
            ['signature' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAFCAYAAABM6GxJAAAAeklEQVQoU53OsQnCYB' .
                'TE8d9XOYELSOo0kjIoom7gCg6U2iqtnRNkiQjOYGuvPEjEfJDGgyse7+7PJVOVOGCFAhf0uGe575lwwg5rVDPBJzpcB08A759ShF' .
                '5ohwULbLDNwJFr8IgFZ9S45fSsFEvDAVuOvwD8owAdsf8AGfMRDpjy0ScAAAAASUVORK5CYII=']
        );
        $response->assertStatus(201)
            ->assertJson(['message' => 'The waiver "' . $waiver->title . '" has been signed successfully.']);

        // Check database
        $this->assertDatabaseHas('user_waiver', [
           'user_id'   => $this->admin->id,
           'waiver_id' => $waiver->id,
        ]);
    }

    /**
     * Test [DELETE] /waivers/active/:waiverID/withdraw resource.
     *
     * @covers \App\Http\Controllers\WaiverController
     * @return void
     */
    public function testActiveWithdraw()
    {
        // Create an active waiver that is signed by the admin
        $waiver = Waiver::factory()->isActive()->hasTexts(3)->create();
        $waiver->users()->attach([$this->admin->id => ['signature' => 'test']]);

        $resource = $this->resource . '/active/' . $waiver->id . '/withdraw';

        // Verify current state
        $this->assertDatabaseHas('user_waiver', [
            'user_id'   => $this->admin->id,
            'waiver_id' => $waiver->id,
        ]);

        // Unauthorized
        $this->checkUnauthorized($resource, 'delete');

        // Sign in as admin without permissions
        $this->actingAs($this->admin, []);

        // Not found
        $this->checkNotFound($this->resource . '/active/9999/withdraw', 'delete');

        // Success
        $response = $this->deleteJson($resource);
        $response->assertStatus(200)
            ->assertJson(['message' => 'The agreement of the waiver has been withdrawn.']);

        // Check database
        $this->assertDatabaseMissing('user_waiver', [
            'user_id'   => $this->admin->id,
            'waiver_id' => $waiver->id,
        ]);
    }

    /**
     * Test [GET] /waivers resource.
     *
     * @covers \App\Http\Controllers\WaiverController
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
            ->assertJsonFragment(['message' => 'Requested filter(s) `invalid` are not allowed. Allowed filter(s) are `id, is_active, title`.']);

        // Invalid sorting
        $response = $this->getJson($this->resource . '?sort=invalid');
        $response->assertStatus(400)
            ->assertJsonFragment(['message' => 'Requested sort(s) `invalid` is not allowed. Allowed sort(s) are `id, is_active, title`.']);

        // Success
        $response = $this->getJson($this->resource);
        $response->assertStatus(200)
            ->assertJsonFragment(['per_page' => 50])
            ->assertJsonFragment(['total' => Waiver::all()->count()]);

        // Filtering
        $response = $this->getJson($this->resource . '?filter[title]=' . $this->waivers->first()->title);
        $count = Waiver::where(
            'title',
            'like',
            '%' .  $this->waivers->first()->title . '%')->count();
        $response->assertStatus(200)->assertJsonFragment(['total' => $count]);
    }

    /**
     * Test [POST] /waivers resource.
     *
     * @covers \App\Http\Controllers\WaiverController
     * @return void
     */
    public function testCreate()
    {
        $json = [
            'is_active' => $this->faker->boolean(50),
            'title'     => $this->faker->words(\rand(2,6), true),
        ];

        // Unauthorized
        $this->checkUnauthorized($this->resource, 'post');

        // Forbidden
        $this->checkForbidden($this->resource, 'post');

        // Sign in as admin
        $this->actingAs($this->admin);

        // Invalid input
        $this->checkInvalidInput($this->resource, 'post', [], [
            'title' => ["The Title field is required."],
        ]);

        // Success
        $response = $this->postJson($this->resource, $json);
        $response->assertStatus(201)
            ->assertJson(['message' => 'The waiver has been created successfully.']);

        // Check database
        $this->assertDatabaseHas('waivers', $json);
    }

    /**
     * Test [PUT] /waivers/:waiverID resource.
     *
     * @covers \App\Http\Controllers\WaiverController
     * @return void
     */
    public function testDeactivate()
    {
        // Create necessary data
        $waiver = Waiver::factory()->isActive()->hasUnassignedWaivers(3)->create();
        $users = User::factory()->count(2)->createActiveUser()->create();
        $waiver->users()->attach($users->pluck('id')->toArray(), ['signature' => 'test']);

        $resource = $this->resource . '/' . $waiver->id;

        // Unauthorized
        $this->checkUnauthorized($resource, 'put');

        // Forbidden
        $this->checkForbidden($resource, 'put');

        // Sign in as admin
        $this->actingAs($this->admin);

        // Not found
        $this->checkNotFound($this->resource . '/9999', 'put');

        // Invalid input
        $this->checkInvalidInput(
            $resource,
            'put',
            ['is_active' => '', 'title' => ''],
            [
                'is_active' => ['The Is active field is required.'],
                'title'     => ['The Title field is required.'],
            ]
        );

        // Verify state before deactivating waiver
        $this->assertEquals(3, $waiver->unassignedWaivers->count());
        $this->assertEquals(2, $waiver->users->count());

        // Success
        $response = $this->putJson($resource, ['is_active' => false]);
        $response->assertStatus(200)->assertJsonFragment(['is_active' => false]);

        // Refresh model
        $waiver->refresh();

        // Verify state after deactivating
        $this->assertEquals(0, $waiver->unassignedWaivers->count());
        $this->assertEquals(0, $waiver->users->count());
    }

    /**
     * Test [DELETE] /waivers/:waiverID resource.
     *
     * @covers \App\Http\Controllers\WaiverController
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

        // Not found
        $this->checkNotFound($this->resource . '/9999', 'delete');

        // Success
        $response = $this->deleteJson($resource);
        $response->assertStatus(200)
            ->assertJson(['message' => 'The waiver has been deleted successfully.']);
        $this->assertDeleted($this->waivers->first());

        // Check related texts are deleted
        foreach ($this->waivers->first()->texts as $text) {
            $this->assertDeleted($text);
        }
    }

    /**
     * Test [DELETE] /waivers resource.
     *
     * @covers \App\Http\Controllers\WaiverController
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
            ['ids' => $this->waivers->pluck('id')->toArray()]
        );
        $response->assertStatus(200)
            ->assertJson(['message' => '3 waivers have been deleted successfully.']);

        // Check database
        foreach ($this->waivers as $waiver) {
            $this->assertDeleted($waiver);
        }

        // Check related texts are deleted
        foreach ($this->waivers as $waiver) {
            foreach ($waiver->texts as $text) {
                $this->assertDeleted($text);
            }
        }
    }

    /**
     * Test [POST] /waivers/:waiverID/duplicate resource.
     *
     * @covers \App\Http\Controllers\WaiverController
     * @return void
     */
    public function testDuplicate()
    {
        // Create waiver with text
        $waiver = Waiver::factory()->isActive()->hasTexts(3)->create();

        $resource = $this->resource . '/' . $waiver->id . '/duplicate';

        // Unauthorized
        $this->checkUnauthorized($resource, 'post');

        // Forbidden
        $this->checkForbidden($resource, 'post');

        // Sign in as admin
        $this->actingAs($this->admin);

        // Not found
        $this->checkNotFound($this->resource . '/9999/duplicate', 'post');

        // Success
        $response = $this->postJson($resource);
        $newWaiver = Waiver::orderBy('id', 'DESC')->first();

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'The waiver has been duplicated successfully.',
                'data'    => $newWaiver->toArray(),
            ]);

        // Check database
        $this->assertDatabaseHas('waivers',
            ['is_active' => 0, 'title' => $newWaiver->title]
        );
        foreach ($newWaiver->texts as $text) {
            $this->assertDatabaseHas('texts', $text->makeHidden('created_at', 'updated_at')->toArray());
        }
    }

    /**
     * Test [GET] /waivers/:waiverID resource.
     *
     * @covers \App\Http\Controllers\WaiverController
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

        // Not found
        $this->checkNotFound($this->resource . '/9999');

        // Success
        $response = $this->getJson($resource);
        $response->assertStatus(200)
            ->assertJson(Waiver::find($this->waivers->first()->id)->toArray());
    }

    /**
     * Test [PUT] /waivers/:waiverID resource.
     *
     * @covers \App\Http\Controllers\WaiverController
     * @return void
     */
    public function testUpdate()
    {
        $resource = $this->resource . '/' . $this->waivers->first()->id;

        // Unauthorized
        $this->checkUnauthorized($resource, 'put');

        // Forbidden
        $this->checkForbidden($resource, 'put');

        // Sign in as admin
        $this->actingAs($this->admin);

        // Not found
        $this->checkNotFound($this->resource . '/9999', 'put');

        // Invalid input
        $this->checkInvalidInput(
            $resource,
            'put',
            ['is_active' => '', 'title' => ''],
            [
                'is_active' => ['The Is active field is required.'],
                'title'     => ['The Title field is required.'],
            ]
        );

        // Success
        $response = $this->putJson(
            $resource,
            ['is_active' => ! $this->waivers->first()->is_active, 'title' => 'My new title',]
        );
        $response->assertStatus(200)
            ->assertJsonFragment(['is_active' => ! $this->waivers->first()->is_active, 'title' => 'My new title',]);

        // Check database
        $this->assertDatabaseHas(
            'waivers',
            ['is_active' => ! $this->waivers->first()->is_active, 'title' => 'My new title',]
        );
    }
}
