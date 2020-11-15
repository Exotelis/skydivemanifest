<?php

namespace Tests\Feature\Qualification;

use App\Models\Qualification;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * Class QualificationResourcesTest
 * @package Tests\Feature\Qualification
 */
class QualificationResourcesTest extends TestCase
{
    use WithFaker;

    /**
     * @var \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|Qualification[]
     */
    protected $qualifications;

    /**
     * @var string
     */
    protected $resource = self::API_URL . 'qualifications';

    public function setUp(): void
    {
        parent::setUp();

        // Create $qualifications
        $this->qualifications = Qualification::factory()->count(3)->create();
    }

    /**
     * Test [GET] /qualifications resource.
     *
     * @covers \App\Http\Controllers\QualificationController
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
            ->assertJsonFragment(['message' => 'Requested filter(s) `invalid` are not allowed. Allowed filter(s) are `color, qualification, slug`.']);

        // Invalid sorting
        $response = $this->getJson($this->resource . '?sort=invalid');
        $response->assertStatus(400)
            ->assertJsonFragment(['message' => 'Requested sort(s) `invalid` is not allowed. Allowed sort(s) are `color, qualification, slug`.']);

        // Success
        $response = $this->getJson($this->resource);
        $response->assertStatus(200)
            ->assertJsonFragment(['per_page' => 50])
            ->assertJsonFragment(['total' => Qualification::all()->count()]);

        // Filtering
        $response = $this->getJson($this->resource . '?filter[slug]=' . $this->qualifications->first()->slug);
        $count = Qualification::where(
            'slug',
            'like',
            '%' .  $this->qualifications->first()->slug . '%')->count();
        $response->assertStatus(200)->assertJsonFragment(['total' => $count]);
    }

    /**
     * Test [POST] /qualifications resource.
     *
     * @covers \App\Http\Controllers\QualificationController
     * @return void
     */
    public function testCreate()
    {
        $word = $this->faker->unique()->word;
        $json = [
            'color'         => $this->faker->hexColor,
            'qualification' => \ucfirst($word),
            'slug'          => \strtolower($word),
        ];

        // Unauthorized
        $this->checkUnauthorized($this->resource, 'post');

        // Forbidden
        $this->checkForbidden($this->resource, 'post');

        // Sign in as admin
        $this->actingAs($this->admin);

        // Invalid input
        $this->checkInvalidInput($this->resource, 'post', ['color' => '000000'], [
            'color'         => ["The Color format is invalid."],
            'qualification' => ["The Qualification field is required."],
            'slug'          => ["The Slug field is required."],
        ]);

        // Success
        $response = $this->postJson($this->resource, $json);
        $response->assertStatus(201)
            ->assertJson(['message' => 'The qualification has been created successfully.']);

        // Check database
        $this->assertDatabaseHas('qualifications', $json);
    }

    /**
     * Test [DELETE] /qualifications/:qualificationSlug resource.
     *
     * @covers \App\Http\Controllers\QualificationController
     * @return void
     */
    public function testDelete()
    {
        $resource = $this->resource . '/' . $this->qualifications->first()->slug;

        // Unauthorized
        $this->checkUnauthorized($resource, 'delete');

        // Forbidden
        $this->checkForbidden($resource, 'delete');

        // Sign in as admin
        $this->actingAs($this->admin);

        // Not found
        $this->checkNotFound($this->resource . '/zzzzz', 'delete');

        // Success
        $response = $this->deleteJson($resource);
        $response->assertStatus(200)
            ->assertJson(['message' => 'The qualification has been deleted successfully.']);
        $this->assertDeleted($this->qualifications->first());
    }

    /**
     * Test [DELETE] /qualifications resource.
     *
     * @covers \App\Http\Controllers\QualificationController
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
            'slugs' => ["The Slugs field is required."]
        ]);

        // Success
        $response = $this->deleteJson(
            $this->resource,
            ['slugs' => $this->qualifications->pluck('slug')->toArray()]
        );
        $response->assertStatus(200)
            ->assertJson(['message' => '3 qualifications have been deleted successfully.']);

        // Check database
        foreach ($this->qualifications as $qualification) {
            $this->assertDeleted($qualification);
        }
    }

    /**
     * Test [GET] /qualifications/:qualificationSlug resource.
     *
     * @covers \App\Http\Controllers\QualificationController
     * @return void
     */
    public function testGet()
    {
        $resource = $this->resource . '/' . $this->qualifications->first()->slug;

        // Unauthorized
        $this->checkUnauthorized($resource);

        // Forbidden
        $this->checkForbidden($resource);

        // Sign in as admin
        $this->actingAs($this->admin);

        // Not found
        $this->checkNotFound($this->resource . '/zzzzz');

        // Success
        $response = $this->getJson($resource);
        $response->assertStatus(200)
            ->assertJson(Qualification::find($this->qualifications->first()->slug)->toArray());
    }

    /**
     * Test [PUT] /qualifications/:qualificationSlug resource.
     *
     * @covers \App\Http\Controllers\QualificationController
     * @return void
     */
    public function testUpdate()
    {
        $resource = $this->resource . '/' . $this->qualifications->first()->slug;

        // Unauthorized
        $this->checkUnauthorized($resource, 'put');

        // Forbidden
        $this->checkForbidden($resource, 'put');

        // Sign in as admin
        $this->actingAs($this->admin);

        // Not found
        $this->checkNotFound($this->resource . '/zzzzz', 'put');

        // Invalid input
        $this->checkInvalidInput(
            $resource,
            'put',
            ['slug' => $this->qualifications->last()->slug],
            ['slug' => ['The Slug has already been taken.']
        ]);

        // Success
        $response = $this->putJson(
            $resource,
            ['color' => '#123456', 'slug' => $this->qualifications->first()->slug,]
        );
        $response->assertStatus(200)
            ->assertJsonFragment(['color' => '#123456']);

        // Check database
        $this->assertDatabaseHas(
            'qualifications',
            ['color' => '#123456', 'slug' => $this->qualifications->first()->slug,]
        );
    }
}
