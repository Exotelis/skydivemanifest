<?php

namespace Tests\Feature\Timezone;

use Tests\TestCase;

/**
 * Class TimezoneResourceTest
 * @package Tests\Feature\Timezone
 */
class TimezoneResourceTest extends TestCase
{
    /**
     * Test tiezones resource.
     *
     * @return void
     */
    public function testTimezones()
    {
        $resource = self::API_URL . 'timezones';

        // Success
        $response = $this->getJson($resource);
        $response->assertStatus(200)->assertJson(\Carbon\CarbonTimeZone::listIdentifiers());
    }
}
