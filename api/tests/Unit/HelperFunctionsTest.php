<?php

namespace Tests\Unit;

use Tests\TestCase;

/**
 * Class HelperFunctionsTest
 * @package Tests\Unit
 */
class HelperFunctionsTest extends TestCase
{
    /**
     * Test if the correct app name is returned.
     *
     * @covers ::appName
     * @return void
     */
    public function testAppName()
    {
        $this->assertEquals(config('app.name'), appName());
        $this->assertIsString(appName());
    }

    /**
     * Test if the correct support mail is returned.
     *
     * @covers ::appSupportMail
     * @return void
     */
    public function testAppSupportMail()
    {
        $this->assertEquals(config('mail.support_mail'), appSupportMail());
        $this->assertIsString(appSupportMail());
    }

    /**
     * Test if the correct timezone is returned.
     *
     * @covers ::appTimezone
     * @return void
     */
    public function testAppTimezone()
    {
        $this->assertEquals(config('app.timezone'), appTimezone());
        $this->assertIsString(appTimezone());
    }

    /**
     * Test if the correct api version is returned.
     *
     * @covers ::apiVersion
     * @return void
     */
    public function testApiVersion()
    {
        $this->assertEquals(config('app.api_version'), apiVersion());
    }

    /**
     * Test if the correct value for 'allowMultipleTokens' is returned.
     *
     * @covers ::allowMultipleTokens
     * @return void
     */
    public function testAllowMultipleTokens()
    {
        $this->assertEquals(config('passport.allow_multiple_tokens'), allowMultipleTokens());
        $this->assertIsBool(allowMultipleTokens());
    }

    /**
     * Test if the correct value for 'deleteInactiveUsers' is returned.
     *
     * @covers ::deleteInactiveUsers
     * @return void
     */
    public function testDeleteInactiveUsers()
    {
        $this->assertEquals(config('app.users.delete_inactive_after'), deleteInactiveUsers());
        $this->assertIsInt(deleteInactiveUsers());
    }

    /**
     * Test if the correct value for 'deleteUnverifiedUsers' is returned.
     *
     * @covers ::deleteUnverifiedUsers
     * @return void
     */
    public function testDeleteUnverifiedUsers()
    {
        $this->assertEquals(config('app.users.delete_unverified_after'), deleteUnverifiedUsers());
        $this->assertIsInt(deleteUnverifiedUsers());
    }

    /**
     * Test if the correct value for 'recoverUsers' is returned.
     *
     * @covers ::recoverUsers
     * @return void
     */
    public function testRecoverUsers()
    {
        $this->assertEquals(config('app.users.recover'), recoverUsers());
        $this->assertIsInt(recoverUsers());
    }

    /**
     * Test if the correct frontend url is returned.
     *
     * @covers ::frontendUrl
     * @return void
     */
    public function testFrontendUrl()
    {
        $this->assertEquals(config('app.frontend_url'), frontendUrl());
        $this->assertIsString(frontendUrl());
    }

    /**
     * Test if the correct regex for strong password is returned.
     *
     * @covers ::regexStrongPassword
     * @return void
     */
    public function testRegexStrongPassword()
    {
        $this->assertIsString(regexStrongPassword());
        $this->assertMatchesRegularExpression(regexStrongPassword(), 'Secret1!');
        $this->assertDoesNotMatchRegularExpression(regexStrongPassword(), 'weakPassword');
    }

    /**
     * Test if the correct regex for medium password is returned.
     *
     * @covers ::regexMediumPassword
     * @return void
     */
    public function testRegexMediumPassword()
    {
        $this->assertIsString(regexMediumPassword());
        $this->assertMatchesRegularExpression(regexMediumPassword(), 'Secret1');
        $this->assertDoesNotMatchRegularExpression(regexMediumPassword(), 'weakPassword');
    }

    /**
     * Test if the correct regex for weak password is returned.
     *
     * @covers ::regexWeakPassword
     * @return void
     */
    public function testRegexWeakPassword()
    {
        $this->assertIsString(regexWeakPassword());
        $this->assertMatchesRegularExpression(regexWeakPassword(), 'longer');
        $this->assertDoesNotMatchRegularExpression(regexWeakPassword(), 'short');
    }

    /**
     * Test if the correct default password strength is returned.
     *
     * @covers ::defaultPasswordStrength
     * @return void
     */
    public function testDefaultPasswordStrength()
    {
        $this->assertEquals(config('auth.password_strength'), defaultPasswordStrength());
        $this->assertIsString(defaultPasswordStrength());
    }

    /**
     * Test if the correct password strength callback is returned.
     *
     * @covers ::passwordStrength
     * @return void
     */
    public function testPasswordStrength()
    {
        $this->assertIsString(passwordStrength());
        $this->assertMatchesRegularExpression(passwordStrength(), 'Secret1!');
        $this->assertDoesNotMatchRegularExpression(passwordStrength(), 'weak');
    }

    /**
     * Test if the correct admin role is returned.
     *
     * @covers ::adminRole
     * @return void
     */
    public function testAdminRole()
    {
        $this->assertEquals(config('app.groups.admin'), adminRole());
        $this->assertIsInt(adminRole());
    }

    /**
     * Test if the correct user role is returned.
     *
     * @covers ::userRole
     * @return void
     */
    public function testUserRole()
    {
        $this->assertEquals(config('app.groups.user'), userRole());
        $this->assertIsInt(userRole());
    }

    /**
     * Test if the correct default role is returned.
     *
     * @covers ::defaultRole
     * @return void
     */
    public function testDefaultRole()
    {
        $this->assertEquals(config('app.groups.user'), defaultRole());
        $this->assertIsInt(defaultRole());
    }

    /**
     * Test if the correct value for 'lockAccountAfter' is returned.
     *
     * @covers ::lockAccountAfter
     * @return void
     */
    public function testLockAccountAfter()
    {
        $this->assertEquals(config('auth.lock.after_tries'), lockAccountAfter());
        $this->assertIsInt(lockAccountAfter());
    }

    /**
     * Test if the correct value for 'lockAccountFor' is returned.
     *
     * @covers ::lockAccountFor
     * @return void
     */
    public function testLockAccountFor()
    {
        $this->assertEquals(config('auth.lock.for_minutes'), lockAccountFor());
        $this->assertIsInt(lockAccountFor());
    }

    /**
     * Test if the correct default locale is returned.
     *
     * @covers ::defaultLocale
     * @return void
     */
    public function testDefaultLocale()
    {
        $this->assertEquals(config('app.locale'), defaultLocale());
        $this->assertIsString(defaultLocale());
    }

    /**
     * Test if the correct valid locales are returned.
     *
     * @covers ::validLocales
     * @return void
     */
    public function testValidLocales()
    {
        $this->assertEquals(config('app.valid_locales'), validLocales());
        $this->assertIsArray(validLocales());
    }

    /**
     * Test if the correct valid locales are returned.
     *
     * @covers ::validGender
     * @return void
     */
    public function testValidGender()
    {
        $this->assertEquals(['m','f','d','u'], validGender());
        $this->assertIsArray(validGender());
    }

    /**
     * Test if the correct valid roles are returned.
     *
     * @covers ::validRoles
     * @return void
     */
    public function testValidRoles()
    {
        $this->assertEquals(0, validRoles()->count());

        $roles = validRoles($this->admin)->pluck('id')->toArray();
        $this->assertEquals([1, 2], $roles);

        $user = \App\Models\User::factory()->isUser()->create();
        $roles = validRoles($user)->pluck('id')->toArray();
        $this->assertEquals([2], $roles);
    }

    /**
     * Test if the correct bool is returned.
     *
     * @covers ::isDigit
     * @return void
     */
    public function testIsDigit()
    {
        $this->assertEquals(false, isDigit('10.5'));
        $this->assertEquals(true, isDigit('10'));
        $this->assertIsBool(isDigit('10'));
    }

    /**
     * Test if the correct value is returned
     *
     * @covers ::currentUserLogString
     * @return void
     */
    public function testCurrentUserLogString()
    {
        $this->assertEquals(null, currentUserLogString());

        // Mock user
        /** @var \App\Models\User $user */
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);

        $this->assertEquals($user->logString(), currentUserLogString());
    }
}
