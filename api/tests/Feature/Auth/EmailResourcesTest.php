<?php

namespace Tests\Feature\Auth;

use App\Facades\VerifyEmail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

/**
 * Class EmailResourcesTest
 * @package Tests\Feature\Auth
 */
class EmailResourcesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test confirm email address resource.
     *
     * @covers \App\Http\Controllers\ConfirmEmailController
     * @return void
     */
    public function testConfirm()
    {
        $resource = self::API_URL . 'auth/email/confirm';
        $user = factory(User::class)->create();
        $newEmail = 'newemail@example.com';

        Event::fake();

        // Invalid request
        $response = $this->postJson($resource, []);
        $response->assertStatus(422)
            ->assertJsonStructure(['message', 'errors'])
            ->assertJsonFragment(['message' => 'The given data was invalid.']);

        // Invalid user
        $response = $this->postJson($resource, ['token' => '0000000000']);
        $response->assertStatus(400)->assertJson(['message' => 'The email confirm token is invalid.']);

        // Token expired
        $token = VerifyEmail::create($user->email, $newEmail);
        $table = 'email_changes';
        $tokenExpired = ['created_at' => Carbon::now()->subMinutes(VerifyEmail::expires())];
        $this->assertDatabaseHas($table, ['email' => $user->email]);
        $this->getConnection()->table($table)->where('email', '=', $user->email)->update($tokenExpired);
        $response = $this->postJson($resource, ['token' => $token]);
        $response->assertStatus(400)->assertJson(['message' => 'The email confirm token has expired.']);

        // Success
        $token = VerifyEmail::create($user->email, $newEmail);
        $response = $this->postJson($resource, ['token' => $token]);
        $response->assertStatus(200)->assertJson(['message' => 'Email address has been verified successfully.']);
        $this->assertDatabaseHas('users', ['email' => $newEmail]);
        Event::assertDispatched(\App\Events\Auth\EmailVerified::class);
    }

    /**
     * Test delete email address resource.
     *
     * @covers \App\Http\Controllers\ConfirmEmailController
     * @return void
     */
    public function testDelete()
    {
        $resource = self::API_URL . 'auth/email/delete';
        $user = factory(User::class)->create();
        $newEmail = 'newemail@example.com';

        // Not signed in
        $response = $this->postJson($resource);
        $response->assertStatus(401)->assertJson(['message' => 'You are not signed in.']);

        // No valid token / No email address change requested
        $this->actingAs($user);
        $response = $this->postJson($resource);
        $response->assertStatus(400)
            ->assertJson(['message' => 'You haven\'t requested an email address change.']);

        // Success
        VerifyEmail::create($user->email, $newEmail);
        $this->assertDatabaseHas('email_changes', ['email' => $user->email]);
        $response = $this->postJson($resource);
        $response->assertStatus(200)
            ->assertJson(['message' => 'Email change request has been deleted successfully.']);
        $this->assertDatabaseMissing('email_changes', ['email' => $user->email]);
    }

    /**
     * Test resend email address resource.
     *
     * @covers \App\Http\Controllers\ConfirmEmailController
     * @return void
     */
    public function testResend()
    {
        $resource = self::API_URL . 'auth/email/resend';
        $user = factory(User::class)->create();
        $newEmail = 'newemail@example.com';

        Notification::fake();

        // Not signed in
        $response = $this->postJson($resource);
        $response->assertStatus(401)->assertJson(['message' => 'You are not signed in.']);

        // No email address change requested
        $this->actingAs($user);
        $response = $this->postJson($resource);
        $response->assertStatus(400)
            ->assertJson(['message' => 'You haven\'t requested an email address change.']);

        // Too many request, it's not allowed to resend the notification so soon after creation
        VerifyEmail::create($user->email, $newEmail);
        $response = $this->postJson($resource);
        $response->assertStatus(429)
            ->assertJson([
                'message' => 'Please wait before retrying. You can resend the verification email once every 5 minutes.'
            ]);

        // Success
        $this->getConnection()
            ->table('email_changes')
            ->where('email', '=', $user->email)
            ->update(['created_at' => Carbon::now()->subMinutes(VerifyEmail::expires())]);
        $response = $this->postJson($resource);
        $response->assertStatus(200)->assertJson(['message' => 'Email has been sent successfully.']);
        Notification::assertSentTo(
            [$user],
            \App\Notifications\VerifyEmail::class
        );
    }
}
