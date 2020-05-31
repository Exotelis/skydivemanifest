<?php

namespace Tests\Unit\Auth;

use App\Http\Controllers\ResetPasswordController;
use PHPUnit\Framework\TestCase;

/**
 * Class ResetPasswordControllerTest
 * @package Tests\Unit
 */
class ResetPasswordControllerTest extends TestCase
{
    /**
     * ResetPasswordController instance
     *
     * @var ResetPasswordController
     */
    protected $controller;

    /**
     * This method is called before each test.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->controller = new ResetPasswordController();
    }

    /**
     * Test the default broker.
     *
     * @return void
     * @covers \App\Http\Controllers\ResetPasswordController
     */
    public function testBroker()
    {
        $this->assertEquals('users', $this->controller->broker);
    }
}
