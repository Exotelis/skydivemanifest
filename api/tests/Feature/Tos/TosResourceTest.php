<?php

namespace Tests\Feature\Tos;

use Tests\TestCase;

/**
 * Class TosResourceTest
 * @package Tests\Feature\Tos
 */
class TosResourceTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testTos()
    {
        $resource = self::API_URL . 'tos';

        $response = $this->getJson($resource);
        $response->assertExactJson(__('tos'));
    }
}
