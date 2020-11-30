<?php

namespace Tests\Feature\Text;

use App\Models\Text;
use App\Models\Waiver;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * Class TextResourcesTest
 * @package Tests\Feature\Text
 */
class TextResourcesTest extends TestCase
{
    use WithFaker;

    /**
     * @var \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|Text[]
     */
    protected $texts;

    /**
     * @var string
     */
    protected $resource;

    /**
     * @var string
     */
    protected $waiverNotFoundResource = self::API_URL . 'waivers/9999/texts';

    public function setUp(): void
    {
        parent::setUp();

        // Texts - with model waiver as relation!!!
        $this->texts = Text::factory()
            ->count(4)
            ->for(Waiver::factory()->isNotActive(), 'textable')
            ->state(new Sequence(
                ['language_code' => 'en', 'position' => 1],
                ['language_code' => 'en', 'position' => 2],
                ['language_code' => 'en', 'position' => 3],
                ['language_code' => 'de', 'position' => 1],
                ['language_code' => 'de', 'position' => 2],
                ['language_code' => 'de', 'position' => 3],
            ))
            ->create();

        $this->resource = self::API_URL . 'waivers/' . $this->texts->first()->textable->id . '/texts';
    }

    /**
     * Test [GET] waivers/:waiverID/texts resource.
     *
     * @covers \App\Http\Controllers\TextController
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

        // Not found - waiver
        $this->checkNotFound($this->waiverNotFoundResource);

        // Invalid filtering
        $response = $this->getJson($this->resource . '?filter[invalid]=somevalue');
        $response->assertStatus(400)
            ->assertJsonFragment(['message' => 'Requested filter(s) `invalid` are not allowed. Allowed filter(s) are `id, language_code, text, title`.']);

        // Invalid sorting
        $response = $this->getJson($this->resource . '?sort=invalid');
        $response->assertStatus(400)
            ->assertJsonFragment(['message' => 'Requested sort(s) `invalid` is not allowed. Allowed sort(s) are `id, language_code, position, text, title`.']);

        // Success
        $response = $this->getJson($this->resource);
        $response->assertStatus(200)
            ->assertJsonFragment(['per_page' => 50])
            ->assertJsonFragment(['total' => Text::all()->count()]);

        // Filtering
        $response = $this->getJson(
            $this->resource . '?filter[language_code]=' . $this->texts->first()->language_code
        );
        $count = Text::where(
            'language_code',
            'like',
            '%' .  $this->texts->first()->language_code . '%')->count();
        $response->assertStatus(200)->assertJsonFragment(['total' => $count]);
    }

    /**
     * Test [POST] waivers/:waiverID/texts resource.
     *
     * @covers \App\Http\Controllers\TextController
     * @return void
     */
    public function testCreate()
    {
        $json = [
            'language_code' => 'en',
            'text'          => $this->faker->text(),
            'title'         => $this->faker->optional(80)->words(\rand(2,6), true),
        ];

        // Unauthorized
        $this->checkUnauthorized($this->resource, 'post');

        // Forbidden
        $this->checkForbidden($this->resource, 'post');

        // Sign in as admin
        $this->actingAs($this->admin);

        // Not found - aircraft
        $this->checkNotFound($this->waiverNotFoundResource, 'post');

        // Invalid input
        $this->checkInvalidInput($this->resource, 'post', ['position' => 0], [
            'language_code' => ['The Language code field is required.'],
            'position'      => ['The Position must be at least 1.'],
            'text'          => ['The Text field is required.'],
        ]);

        // Success - without position and check database
        $response = $this->postJson($this->resource, $json);
        $response->assertStatus(201)->assertJson(['message' => 'The text has been created successfully.']);
        $this->assertDatabaseHas('texts', \array_merge($json, ['position' => 4]));

        // Success - with position without causing a reposition and check database
        $response = $this->postJson($this->resource, \array_merge($json, ['position' => 99]));
        $response->assertStatus(201)->assertJson(['message' => 'The text has been created successfully.']);
        $this->assertDatabaseHas('texts', \array_merge($json, ['position' => 5]));

        // Success - with position with causing a reposition and check database
        $response = $this->postJson($this->resource, \array_merge($json, ['language_code' => 'de', 'position' => 2]));
        $response->assertStatus(201)->assertJson(['message' => 'The text has been created successfully.']);
        $this->assertDatabaseHas('texts', \array_merge($json, ['language_code' => 'de', 'position' => 2]));

        foreach ($this->texts as $text) {
            if ($text->language_code === 'en' || $text->position <= 1) {
                continue;
            }

            $this->assertDatabaseHas('texts', [
                'id'            => $text->id,
                'language_code' => 'de',
                'position'      => $text->position + 1,
            ]);
        }
    }

    /**
     * Test [DELETE] waivers/:waiverID/texts/:textID resource.
     *
     * @covers \App\Http\Controllers\TextController
     * @return void
     */
    public function testDelete()
    {
        $resource = $this->resource . '/' . $this->texts->first()->id;

        // Unauthorized
        $this->checkUnauthorized($resource, 'delete');

        // Forbidden
        $this->checkForbidden($resource, 'delete');

        // Sign in as admin
        $this->actingAs($this->admin);

        // Not found - waiver
        $this->checkNotFound(
            $this->waiverNotFoundResource . '/' . $this->texts->first()->id, 'delete');

        // Not found - text
        $this->checkNotFound($this->resource . '/9999', 'delete');

        // Not found - texts of different waiver
        $text = Text::factory()->for(Waiver::factory(), 'textable')->create();
        $this->checkNotFound($this->resource . '/' . $text->id, 'delete');

        // Success
        $response = $this->deleteJson($resource);
        $response->assertStatus(200)->assertJson(['message' => 'The text has been deleted successfully.']);
        $this->assertDeleted($this->texts->first());

        // Check database
        $this->assertDeleted($this->texts->first());

        // Check if other models have been repositioned
        foreach ($this->texts as $text) {
            $updatedModel = $this->texts->first();
            if ($text->language_code !== $updatedModel->language_code || $text->id === $updatedModel->id) {
                continue;
            }

            $this->assertDatabaseHas('texts', ['id' => $text->id, 'position' => $text->position - 1]);
        }
    }

    /**
     * Test [DELETE] waivers/:waiverID/texts resource.
     *
     * @covers \App\Http\Controllers\TextController
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
        $this->checkNotFound($this->waiverNotFoundResource, 'delete');

        // Invalid input
        $this->checkInvalidInput($this->resource, 'delete', [], [
            'ids' => ["The ids field is required."]
        ]);

        // Success
        $response = $this->deleteJson($this->resource, ['ids' => $this->texts->pluck('id')->toArray()]);
        $response->assertStatus(200)->assertJson(['message' => '4 texts have been deleted successfully.']);
        foreach ($this->texts as $text) {
            $this->assertDeleted($text);
        }

        // Success - Not delete text of different waiver
        $text = Text::factory()->for(Waiver::factory(), 'textable')->create();
        $response = $this->deleteJson($this->resource, ['ids' => [$text->id]]);
        $response->assertStatus(200)->assertJson(['message' => 'No texts have been deleted.']);
    }

    /**
     * Test [GET] waivers/:waiverID/texts/:textID resource.
     *
     * @covers \App\Http\Controllers\TextController
     * @return void
     */
    public function testGet()
    {
        $resource = $this->resource . '/' . $this->texts->first()->id;

        // Unauthorized
        $this->checkUnauthorized($resource);

        // Forbidden
        $this->checkForbidden($resource);

        // Sign in as admin
        $this->actingAs($this->admin);

        // Not found - waiver
        $this->checkNotFound($this->waiverNotFoundResource . '/' . $this->texts->first()->id);

        // Not found - text
        $this->checkNotFound($this->resource . '/9999');

        // Not found - text of different waiver
        $text = Text::factory()->for(Waiver::factory(), 'textable')->create();
        $this->checkNotFound($this->resource . '/' . $text->id);

        // Success
        $response = $this->getJson($resource);
        $response->assertStatus(200)
            ->assertJson(Text::find($this->texts->first()->id)->toArray());
    }

    /**
     * Test [PUT] waivers/:waiverID/texts/:textID resource.
     *
     * @covers \App\Http\Controllers\TextController
     * @return void
     */
    public function testUpdate()
    {
        $resource = $this->resource . '/' . $this->texts->first()->id;

        // Unauthorized
        $this->checkUnauthorized($resource, 'put');

        // Forbidden
        $this->checkForbidden($resource, 'put');

        // Sign in as admin
        $this->actingAs($this->admin);

        // Not found - waiver
        $this->checkNotFound($this->waiverNotFoundResource . '/' . $this->texts->first()->id, 'put');

        // Not found - text
        $this->checkNotFound($this->resource . '/9999', 'put');

        // Not found - texts of different waiver
        $text = Text::factory()->for(Waiver::factory(), 'textable')->create();
        $this->checkNotFound($this->resource . '/' . $text->id, 'put');

        // Invalid input
        $this->checkInvalidInput($resource, 'put', [
            'position' => 0,
            'text'     => '',
        ], [
            'position' => ['The Position must be at least 1.'],
            'text'     => ['The Text field is required.'],
        ]);

        // Success - Move from 1st to 3rd position
        $response = $this->putJson($resource, ['position' => 9999, 'text' => 'Text', 'title' => 'Title']);
        $response->assertStatus(200)->assertJsonFragment(['position' => 3, 'text' => 'Text', 'title' => 'Title']);

        // Check database
        $this->assertDatabaseHas('texts', [
            'id' => $this->texts->first()->id,
            'position' => 3,
            'text' => 'Text',
            'title' => 'Title',
        ]);
        foreach ($this->texts as $text) {
            $updatedModel = $this->texts->first();
            if ($text->language_code !== $updatedModel->language_code || $text->id === $updatedModel->id) {
                continue;
            }

            $this->assertDatabaseHas('texts', ['id' => $text->id, 'position' => $text->position - 1]);
        }

        // Success - Move from 3rd to 1st position
        $response = $this->putJson($resource, ['position' => 1]);
        $response->assertStatus(200)->assertJsonFragment(['position' => 1]);

        // Check database
        $this->assertDatabaseHas('texts', ['id' => $this->texts->first()->id, 'position' => 1]);
        foreach ($this->texts as $text) {
            $updatedModel = $this->texts->first();
            if ($text->language_code !== $updatedModel->language_code || $text->id === $updatedModel->id) {
                continue;
            }

            $this->assertDatabaseHas('texts', ['id' => $text->id, 'position' => $text->position]);
        }
    }

    /**
     * Test if no changes can be made when the parent model is_active
     *
     * @covers \App\Http\Controllers\TextController
     * @return void
     */
    public function testProtectFromChanges()
    {
        // Create active waiver
        $waiver = Waiver::factory()->isActive()->hasTexts(3)->create();

        $resource = self::API_URL . 'waivers/' . $waiver->id . '/texts/' . $waiver->texts->first()->id;

        // Sign in as admin
        $this->actingAs($this->admin);

        // Bad Request
        $response = $this->deleteJson($resource);
        $response->assertStatus(400)
            ->assertJson(['message' => 'The element, you want to add the text to, is active. Active elements cannot ' .
                'be changed. Please deactivate the element first.']);
    }
}
