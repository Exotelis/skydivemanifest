<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

/**
 * Class PasswordResourcesTest
 * @package Tests\Feature
 */
class PasswordResourcesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test change password resource.
     *
     * @covers \App\Http\Controllers\ResetPasswordController
     * @return void
     */
    public function testChangePassword()
    {
        $resource = self::API_URL . 'auth/password/change';
        $json = [
            'password'                  => 'secret',
            'new_password'              => 'Secret1!',
            'new_password_confirmation' => 'Secret1!',
        ];

        // Unauthorized
        $response = $this->postJson($resource, $json);
        $response->assertStatus(401)->assertJson(['message' => 'You are not signed in.']);

        // Forbidden - No password change required
        $this->actingAs(factory(User::class)->states('noPasswordChange')->create());
        $response = $this->postJson($resource, $json);
        $response->assertStatus(403)->assertJson(['message' => 'This action is unauthorized.']);

        // Invalid request
        $this->actingAs(factory(User::class)->states(['passwordChange'])->create());
        $response = $this->postJson($resource, ['password' => 'notvalid']);
        $response
            ->assertStatus(422)->assertJsonStructure(['message', 'errors'])
            ->assertJsonFragment(['message' => 'The given data was invalid.']);

        // Success
        $response = $this->postJson($resource, $json);
        $response->assertJson(['message' => 'Your password has been changed successfully.']);
        $response->assertStatus(200);
    }

    /**
     * Test forgot password resource.
     *
     * @covers \App\Traits\ResetPassword
     * @return void
     */
    public function testForgotPassword()
    {
        $resource = self::API_URL . 'auth/password/forgot';
        $user = factory(User::class)->create();

        Notification::fake();

        // Does not exist
        $response = $this->postJson($resource, ['username' => 'test@example.com']);
        $response->assertStatus(400)->assertJson(['message' => 'Your password cannot be reset.']);

        // Invalid request
        $response = $this->postJson($resource, ['username' => '']);
        $response->assertStatus(422)
            ->assertJsonStructure(['message', 'errors'])
            ->assertJsonFragment(['message' => 'The given data was invalid.']);

        // Invalid user
        $response = $this->postJson($resource, ['username' => 'invaliduser@example.com']);
        $response->assertStatus(400)->assertJson(['message' => 'Your password cannot be reset.']);

        // Success
        $response = $this->postJson($resource, ['username' => $user->email]);
        $response->assertStatus(200)->assertJson(['message' => 'We have emailed your password reset link!']);
        Notification::assertSentTo(
            [$user],
            \App\Notifications\ResetPassword::class
        );

        // Throttled
        $response = $this->postJson($resource, ['username' => $user->email]);
        $response->assertStatus(429)->assertJson([
            'message' => 'Please wait before retrying. You can request a new password once every 10 minutes.'
        ]);
    }

    /**
     * Test reset password resource.
     *
     * @covers \App\Traits\ResetPassword
     * @return void
     */
    public function testResetPassword()
    {
        $resource = self::API_URL . 'auth/password/reset';
        $user = factory(User::class)->create();
        $token = Password::broker()->createToken($user);

        $json = [
            'email'                 => $user->email,
            'password'              => 'NewPassword1!',
            'password_confirmation' => 'NewPassword1!',
            'token'                 => $token,
        ];

        // Invalid request
        $response = $this->postJson($resource, ['email' => 'novalidemail@example.com']);
        $response->assertStatus(422)
            ->assertJsonStructure(['message', 'errors'])
            ->assertJsonFragment(['message' => 'The given data was invalid.']);

        // Invalid user
        $invalidToken = $json;
        $invalidToken['token'] = 'invalid' . $invalidToken['email'];
        $response = $this->postJson($resource, $invalidToken);
        $response->assertStatus(400)
            ->assertJson(['message' => 'The password reset token is invalid or expired.']);

        //Success
        $response = $this->postJson($resource, $json);
        $response->assertStatus(200)->assertJson(['message' => 'Your password has been reset!']);
    }
}
