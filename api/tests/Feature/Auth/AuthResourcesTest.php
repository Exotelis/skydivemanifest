<?php

namespace Tests\Feature\Auth;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Laravel\Passport\Passport;
use Tests\TestCase;

/**
 * Class AuthResourcesTest
 * @package Tests\Feature\Auth
 */
class AuthResourcesTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        // Create oAuth clients
        $clientRepository = new \Laravel\Passport\ClientRepository();
        $password = $clientRepository->createPasswordGrantClient(
            null, 'Test Password Access Client', 'http://localhost'
        );
        $this->app['config']->set(['passport.password_client' => [
            'id'     => $password->id,
            'secret' => $password->getPlainSecretAttribute(),
        ]]);
        $personal = $clientRepository->createPersonalAccessClient(
            null, 'Test Personal Access Client', 'http://localhost'
        );
        $this->app['config']->set(['passport.personal_access_client' => [
            'id'     => $personal->id,
            'secret' => $personal->getPlainSecretAttribute(),
        ]]);

        // Define scopes
        try {
            $validScopes = Permission::all()->pluck('name', 'slug')->all();
        } catch (\Exception $exception) {
            $validScopes = [];
        }
        $defaultScopes = [];
        Passport::tokensCan($validScopes);
        Passport::setDefaultScope($defaultScopes);
    }

    /**
     * Test accept terms of service resource
     *
     * @covers \App\Http\Controllers\AuthController
     * @return void
     */
    public function testAcceptTos()
    {
        $resource = self::API_URL . 'auth/tos';
        $user = User::factory()->isUser()->tosNotAccepted()->create();

        // Unauthorized
        $response = $this->postJson($resource, ['tos' => true]);
        $response->assertStatus(401)->assertJson(['message' => 'You are not signed in.']);

        $this->actingAs($user);

        // Unprocessable Entity
        $this->checkInvalidInput(
            $resource,
            'post',
            ['tos' => false],
            [
                'tos' => ['You have to agree to the terms of service.']
            ]
        );

        // Success
        $response = $this->postJson($resource, ['tos' => true]);
        $response->assertStatus(200)->assertJson(['message' => 'The Terms of Service have been accepted.']);
        $this->assertDatabaseHas('users', ['id' => $user->id, 'tos' => 1]);
    }

    /**
     * Test login resource.
     *
     * @covers \App\Http\Controllers\AuthController
     * @return void
     */
    public function testLogin()
    {
        $resource = self::API_URL . 'auth';
        $user = User::factory()->isAdmin()->create();

        Event::fake();
        Notification::fake();

        // Invalid request
        $response = $this->postJson($resource, ['password' => 'secret']);
        $response->assertStatus(422)
            ->assertJsonStructure(['message', 'errors'])
            ->assertJsonFragment(['message' => 'The given data was invalid.']);

        // Invalid request with cookie
        $response = $this->postJson($resource, ['password' => 'secret'], ['X-Requested-With' => 'XMLHttpRequest']);
        $response->assertStatus(422)
            ->assertJsonStructure(['message', 'errors'])
            ->assertJsonFragment(['message' => 'The validation of the submitted data has been failed.']);

        // Invalid user
        $response = $this->postJson($resource, ['username' => 'unknownuser', 'password' => 'secret']);
        $response->assertStatus(401)
            ->assertJson(['message' => 'The username or password you entered is incorrect.']);

        // Incorrect user credentials
        $response = $this->postJson($resource, ['username' => $user->email, 'password' => 'wrongpassword']);
        $response->assertStatus(401)
            ->assertJson(['message' => 'The username or password you entered is incorrect.']);
        $this->assertDatabaseHas('users', ['email' => $user->email, 'failed_logins' => 1]);

        // Success
        $response = $this->postJson($resource, ['username' => $user->email, 'password' => 'secret']);
        $response->assertStatus(200)->assertJsonStructure(['expires_in', 'access_token', 'refresh_token']);
        $response = $this->postJson($resource, ['username' => $user->username, 'password' => 'secret']);
        $response->assertStatus(200)->assertJsonStructure(['expires_in', 'access_token', 'refresh_token']);
        $this->assertDatabaseMissing('users', ['email' => $user->email, 'last_logged_in' => null]);
        $response->assertCookieMissing('XSRF-TOKEN')->assertCookieMissing('AUTH-TOKEN');

        // Success with Cookies
        $response = $this->postJson(
            $resource,
            ['username' => $user->email, 'password' => 'secret'],
            ['X-Requested-With' => 'XMLHttpRequest']);
        $response->assertCookie('XSRF-TOKEN')->assertCookie('AUTH-TOKEN');

        // Account locked
        for ($i = 0; $i <= lockAccountAfter(); $i++) {
            $response = $this->postJson($resource, ['username' => $user->username, 'password' => 'wrongpassword']);
        }

        $this->assertDatabaseMissing('users', ['username' => $user->username, 'lock_expires' => null]);
        $response->assertStatus(401)->assertJson(['message' => 'Your account has been locked temporarily, ' .
            'because you entered incorrect user credentials too often. It will be unlocked in 29 minutes.']);
        Event::assertDispatched(\App\Events\Auth\LockAccount::class);
        Notification::assertSentTo(
            [$user],
            \App\Notifications\LockAccount::class
        );
    }

    /**
     * Test logout resource.
     *
     * @covers \App\Http\Controllers\AuthController
     * @return void
     */
    public function testLogout()
    {
        $resource = self::API_URL . 'auth/logout';
        $resourceLogin = self::API_URL . 'auth';
        $user = User::factory()->isAdmin()->create();
        $this->actingAs($user);

        // Get token
        $response = $this->postJson($resourceLogin, ['username' => $user->email, 'password' => 'secret']);
        $response->assertStatus(200)->assertJsonStructure(['expires_in', 'access_token', 'refresh_token']);
        $token = $response->json('access_token');
        $this->assertDatabaseHas('oauth_access_tokens', ['user_id' => $user->id, 'revoked' => 0]);

        // Success
        $response = $this->postJson($resource, [], ['Authorization' => $token]);
        $response->assertStatus(200)->assertJson(['message' => 'Logged out successfully.']);
        $this->assertDatabaseHas('oauth_access_tokens', ['user_id' => $user->id, 'revoked' => 1]);
    }

    /**
     * Test refresh resource.
     *
     * @covers \App\Http\Controllers\AuthController
     * @return void
     */
    public function testRefresh()
    {
        $resource = self::API_URL . 'auth/refresh';
        $loginResource = self::API_URL . 'auth';
        $user = User::factory()->isAdmin()->create();

        // Sign in
        $response = $this->postJson($loginResource, ['username' => $user->email, 'password' => 'secret']);
        $refreshToken = $response->original['refresh_token'];

        // Invalid request
        $response = $this->postJson($resource, []);
        $response->assertStatus(422)
            ->assertJsonStructure(['message', 'errors'])
            ->assertJsonFragment(['message' => 'The given data was invalid.']);

        // Incorrect token
        $response = $this->postJson($resource, ['token' => 'wrongtoken']);
        $response->assertStatus(401)
            ->assertJson(['message' => 'Could not get an access token.']);

        // Success
        $response = $this->postJson($resource, ['token' => $refreshToken]);
        $response->assertStatus(200)->assertJsonStructure(['expires_in', 'access_token', 'refresh_token']);
    }

    /**
     * Test register resource.
     *
     * @covers \App\Http\Controllers\AuthController
     * @return void
     */
    public function testRegister()
    {
        $resource = self::API_URL . 'auth/register';
        $json = [
            'dob'                   => $this->faker->date($format = 'Y-m-d', $max = 'now'),
            'email'                 => 'exotelis@mailbox.org',
            'firstname'             => $this->faker->firstName,
            'gender'                => $this->faker->randomElement(validGender()),
            'lastname'              => $this->faker->lastName,
            'password'              => 'Secret1!',
            'password_confirmation' => 'Secret1!',
            'tos'                   => 1
        ];

        Notification::fake();

        // Invalid request
        $response = $this->postJson($resource, ['username' => $this->faker->unique()->userName]);
        $response->assertStatus(422)
            ->assertJsonStructure(['message', 'errors'])
            ->assertJsonFragment(['message' => 'The given data was invalid.']);

        // Success
        $response = $this->postJson($resource, $json);
        $response->assertStatus(201)->assertJson(['message' => 'The user has been created successfully.']);
        $user = User::find($response['data']['id']);
        unset($json['password'], $json['password_confirmation'], $json['tos']);
        $json['is_active'] = true;
        $this->assertDatabaseHas('users', $json);
        $json['role']['id'] = defaultRole();
        Notification::assertSentTo(
            [$user],
            \App\Notifications\CreateUser::class
        );
    }

    /**
     * Test restore resource.
     *
     * @covers \App\Http\Controllers\AuthController
     * @return void
     */
    public function testRecover()
    {
        $resource = self::API_URL . 'auth/recover';
        $user = User::factory()->isUser()->create();

        Notification::fake();

        // Bad request
        $response = $this->postJson($resource, ['token' => '0123456789']);
        $response->assertStatus(400)->assertJson(['message' => 'The token to recover your account is invalid.']);

        // Invalid input
        $this->checkInvalidInput($resource, 'post');

        // Delete user and get token to prepare the restore process
        $user->delete();
        $token = 'myMockedToken';
        $result = DB::table('restore_users')
            ->where('user_id', '=', $user->id)
            ->update(['token' => Hash::make($token)]);

        // Restore user
        $response = $this->postJson($resource, ['token' => $token]);
        $response->assertStatus(200)->assertJson(['message' => 'Your account has been recovered successfully.']);
        Notification::assertSentTo(
            [$user],
            \App\Notifications\RestoreUser::class
        );
    }
}
