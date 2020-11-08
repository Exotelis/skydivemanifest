<?php

namespace Tests\Feature\Currency;

use App\Models\Currency;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * Class CurrencyResourcesTest
 * @package Tests\Feature\Currency
 */
class CurrencyResourcesTest extends TestCase
{
    use WithFaker;

    /**
     * @var Currency
     */
    protected $currencies;

    /**
     * @var string
     */
    protected $resource = self::API_URL . 'currencies';

    public function setUp(): void
    {
        parent::setUp();

        // Create currencies
        $this->currencies = Currency::factory()->count(3)->create();
    }

    /**
     * Test [GET] currencies resource.
     *
     * @covers \App\Http\Controllers\CurrencyController
     * @return void
     */
    public function testAll()
    {
        // Unauthorized
        $this->checkUnauthorized($this->resource);

        // Forbidden - Reading currencies is a default permission. Therefore, set permissions manually.
        $this->actingAs($this->admin, ['invalid']);
        $response = $this->getJson($this->resource);
        $response->assertStatus(403)->assertJson(['message' => 'Invalid scope(s) provided.']);

        // Sign in as admin
        $this->actingAs($this->admin);

        // Invalid filtering
        $response = $this->getJson($this->resource . '?filter[invalid]=somevalue');
        $response->assertStatus(400)
            ->assertJsonFragment(['message' => 'Requested filter(s) `invalid` are not allowed. Allowed filter(s) are `code, currency, symbol`.']);

        // Invalid sorting
        $response = $this->getJson($this->resource . '?sort=invalid');
        $response->assertStatus(400)
            ->assertJsonFragment(['message' => 'Requested sort(s) `invalid` is not allowed. Allowed sort(s) are `code, currency, symbol`.']);

        // Success
        $response = $this->getJson($this->resource);
        $response
            ->assertStatus(200)
            ->assertJsonFragment(['per_page' => 50])
            ->assertJsonFragment(['total' => Currency::all()->count()]);

        // Filtering
        $response = $this->getJson($this->resource . '?filter[code]=' . $this->currencies->first()->code);
        $count = Currency::where('code', 'like', '%' .  $this->currencies->first()->code . '%')
            ->count();
        $response
            ->assertStatus(200)
            ->assertJsonFragment(['total' => $count]);
    }

    /**
     * Test [POST] currencies resource.
     *
     * @covers \App\Http\Controllers\CurrencyController
     * @return void
     */
    public function testCreate()
    {
        $json = [
            'code'     => $this->faker->unique()->currencyCode,
            'currency' => 'Dollar',
            'symbol'   => '$',
        ];

        // Unauthorized
        $this->checkUnauthorized($this->resource, 'post');

        // Forbidden
        $this->checkForbidden($this->resource, 'post');

        // Sign in as admin
        $this->actingAs($this->admin);

        // Invalid input
        $this->checkInvalidInput($this->resource, 'post', ['symbol' => '€'], [
            'code'     => ["The Code field is required."],
            'currency' => ["The Currency field is required."]
        ]);

        // Success
        $response = $this->postJson($this->resource, $json);
        $response->assertStatus(201)->assertJson(['message' => 'The currency has been created successfully.']);

        // Check database
        $this->assertDatabaseHas('currencies', $json);
    }

    /**
     * Test [DELETE] currencies/:currencyCode resource.
     *
     * @covers \App\Http\Controllers\CurrencyController
     * @return void
     */
    public function testDelete()
    {
        $resource = $this->resource . '/' . $this->currencies->first()->code;

        // Unauthorized
        $this->checkUnauthorized($resource, 'delete');

        // Forbidden
        $this->checkForbidden($resource, 'delete');

        // Sign in as admin
        $this->actingAs($this->admin);

        // Not found
        $this->checkNotFound($this->resource . '/ZZZ', 'delete');

        // Success
        $response = $this->deleteJson($resource);
        $response->assertStatus(200)->assertJson(['message' => 'The currency has been deleted successfully.']);
        $this->assertDeleted($this->currencies->first());
    }

    /**
     * Test [DELETE] currencies resource.
     *
     * @covers \App\Http\Controllers\CurrencyController
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
            'codes' => ["The Codes field is required."]
        ]);

        // Success
        $response = $this->deleteJson($this->resource, ['codes' => $this->currencies->pluck('code')->toArray()]);
        $response->assertStatus(200)->assertJson(['message' => '3 currencies have been deleted successfully.']);
        foreach ($this->currencies as $currency) {
            $this->assertDeleted($currency);
        }
    }

    /**
     * Test [GET] currencies/:currencyCode resource.
     *
     * @covers \App\Http\Controllers\CurrencyController
     * @return void
     */
    public function testGet()
    {
        $resource = $this->resource . '/' . $this->currencies->first()->code;

        // Unauthorized
        $this->checkUnauthorized($resource);

        // Forbidden - Reading currencies is a default permission. Therefore, set permissions manually.
        $this->actingAs($this->admin, ['invalid']);
        $response = $this->getJson($resource);
        $response->assertStatus(403)->assertJson(['message' => 'Invalid scope(s) provided.']);

        // Sign in as admin
        $this->actingAs($this->admin);

        // Not found
        $this->checkNotFound($this->resource . '/ZZZ');

        // Success
        $response = $this->getJson($resource);
        $response->assertStatus(200)->assertJson(Currency::find($this->currencies->first()->code)->toArray());
    }

    /**
     * Test [PUT] currencies/:currencyCode resource.
     *
     * @covers \App\Http\Controllers\CurrencyController
     * @return void
     */
    public function testUpdate()
    {
        $resource = $this->resource . '/' . $this->currencies->first()->code;

        // Unauthorized
        $this->checkUnauthorized($resource, 'put');

        // Forbidden
        $this->checkForbidden($resource, 'put');

        // Sign in as admin
        $this->actingAs($this->admin);

        // Not found
        $this->checkNotFound($this->resource . '/ZZZ', 'put');

        // Invalid input
        $this->checkInvalidInput($resource, 'put', ['code' => $this->currencies->last()->code], [
            'code' => ['The Code has already been taken.']
        ]);

        // Success
        $response = $this->putJson($resource, ['code' => $this->currencies->first()->code, 'symbol' => '$']);
        $response->assertStatus(200)
            ->assertJsonFragment(['symbol' => '$']);

        // Check database
        $this->assertDatabaseHas('currencies', [
            'code' => $this->currencies->first()->code,
            'symbol' => '$',
        ]);
    }
}
