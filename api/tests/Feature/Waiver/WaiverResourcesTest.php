<?php

namespace Tests\Feature\Waiver;

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
    public function testActive()
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
