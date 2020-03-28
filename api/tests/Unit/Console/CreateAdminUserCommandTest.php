<?php

namespace Tests\Unit\Console;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Class CreateAdminUserCommandTest
 * @package Tests\Unit\Console
 */
class CreateAdminUserCommandTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test createAdminUser artisan command.
     *
     * @covers \App\Console\Commands\CreateAdminUserCommand
     * @return void
     */
    public function testConsoleCommand()
    {
        $this->artisan('make:admin')
            ->expectsQuestion('What is your first name?', 'John')
            ->expectsQuestion('What is your last name?', 'Doe')
            ->expectsQuestion('What is your email address?', 'johndoe@example.com')
            ->expectsQuestion('What is the username?', 'johndoe')
            ->expectsQuestion('What is the password?', 'secret')
            ->expectsQuestion('What is your birthday?', '20.06.1989')
            ->expectsQuestion('What is your gender?', 'm')
            ->expectsOutput('Administrator johndoe created successfully')
            ->assertExitCode(0);

        $this->assertDatabaseHas('users', [
            'email'     => 'johndoe@example.com',
            'is_active' => 1,
            'role_id'   => adminRole(),
            'username'  => 'johndoe',
        ]);
    }

    /**
     * Test createAdminUser artisan command with options.
     *
     * @covers \App\Console\Commands\CreateAdminUserCommand
     * @return void
     */
    public function testConsoleCommandWithOptions()
    {
        $this->artisan('make:admin --firstname=John --lastname=Doe --email=johndoe@example.com ' .
            '--username=johndoe --password=secret --dob=1989-06-20 --gender=m')
            ->expectsOutput('Administrator johndoe created successfully')
            ->assertExitCode(0);

        $this->assertDatabaseHas('users', [
            'email'     => 'johndoe@example.com',
            'is_active' => 1,
            'role_id'   => adminRole(),
            'username'  => 'johndoe',
        ]);
    }

    /**
     * Test dob validation.
     *
     * @covers \App\Console\Commands\CreateAdminUserCommand
     * @return void
     */
    public function testConsoleCommandDob()
    {
        $this->artisan('make:admin --firstname=John --lastname=Doe --email=johndoe@example.com ' .
            '--username=johndoe --password=secret --gender=m')
            ->expectsQuestion('What is your birthday?', 'invaliddate')
            ->expectsOutput(' Invalid format. Please try again.')
            ->expectsQuestion('What is your birthday?', '2100-06-20')
            ->expectsOutput(' Invalid format. Please try again.')
            ->expectsQuestion('What is your birthday?', '1989-06-20')
            ->expectsOutput('Administrator johndoe created successfully')
            ->assertExitCode(0);

        $this->assertDatabaseHas('users', [
            'email'     => 'johndoe@example.com',
            'is_active' => 1,
            'role_id'   => adminRole(),
            'username'  => 'johndoe',
        ]);
    }
}
