<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/**
 * Authentication
 */
Route::prefix('auth')->group(function() {
    Route::post('/', 'AuthController@login')->name('api.login');
    Route::post('logout', 'AuthController@logout')
        ->middleware('auth:api')
        ->name('api.logout');
    Route::post('refresh', 'AuthController@refresh')->name('api.refresh');
    Route::post('register', 'AuthController@register')->name('api.register');

    /**
     * Email
     */
    Route::prefix('email')->group(function() {
        Route::post('confirm', 'ConfirmEmailController@confirm')->name('api.confirm-email');
        Route::post('delete', 'ConfirmEmailController@delete')
            ->middleware('auth:api')
            ->name('api.delete-email-request');
        Route::post('resend', 'ConfirmEmailController@resend')
            ->middleware('auth:api')
            ->name('api.resend-email-request');
    });

    /**
     * Password
     */
    Route::prefix('password')->group(function() {
        Route::post('change', 'ResetPasswordController@changePassword')
            ->middleware('auth:api')
            ->name('api.change-password');
        Route::post('forgot', 'ResetPasswordController@postEmail')->name('api.forgot-password');
        Route::post('reset', 'ResetPasswordController@postReset')->name('api.reset-password');
    });
});

/**
 * Permissions
 */
Route::middleware(['auth:api', 'scopes:permissions:read', 'verified'])->prefix('permissions')->group(function () {
    Route::get('/', 'PermissionController@all')->name('api.get-permissions');
});

/**
 * Personal
 */
Route::middleware(['auth:api', 'verified'])->prefix('me')->group(function () {
    Route::get('/', function (Request $request) {
        return $request->user();
    });
});

/**
 * Roles
 */
Route::middleware(['auth:api', 'scopes:roles:read', 'verified'])->prefix('roles')->group(function () {
    Route::get('/', 'RoleController@all')->name('api.get-roles');
    Route::post('/', 'RoleController@create')->middleware('scopes:roles:write')
        ->name('api.create-role');
    Route::delete('/', 'RoleController@deleteBulk')->middleware('scopes:roles:delete')
        ->name('api.delete-roles');

    /**
     * Single role
     */
    Route::prefix('{id}')->where(['id' => '[0-9]+'])->group(function() {
        Route::get('/', 'RoleController@role')->name('api.get-role');
        Route::put('/', 'RoleController@update')->middleware('scopes:roles:write')
            ->name('api.update-role');
        Route::delete('/', 'RoleController@delete')->middleware('scopes:roles:delete')
            ->name('api.delete-role');
        // TODO - add/{id}/users to get a list of users or add/remove them from the role
    });
});

/**
 * Roles names
 */
Route::middleware(['auth:api', 'scope:roles:read,users:read', 'verified'])->get('roles/names', function() {
    return \App\Models\Role::all()->pluck('name')->toArray();
})->name('api.get-roles-names');

/**
 * Timezones
 */
Route::get('timezones', function() {
   return \Carbon\CarbonTimeZone::listIdentifiers();
})->name('api.timezones');

/**
 * Users
 */
Route::middleware(['auth:api', 'scopes:users:read', 'verified'])->prefix('users')->group(function() {
    Route::get('/', 'UserController@all')->name('api.get-users');
    Route::post('/', 'UserController@create')->middleware('scopes:users:write')
        ->name('api.create-user');
    Route::delete('/', 'UserController@bulkDelete')->middleware('scopes:users:delete')
        ->name('api.delete-users');

    Route::delete('/{id}', 'UserController@delete')->middleware('scopes:users:delete')
        ->name('api.delete-user')->where('id', '[0-9]+');

    /**
     * Trashed
     */
    Route::middleware('scopes:users:delete')->prefix('trashed')->group(function() {
        Route::get('/', 'UserController@trashed')->name('api.get-trashed-users');
        Route::put('/', 'UserController@restore')->name('api.restore-trashed-users');
        Route::delete('/', 'UserController@deletePermanently')->name('api.delete-trashed-users');
    });
});
