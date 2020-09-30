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
    Route::post('/', [App\Http\Controllers\AuthController::class, 'login'])->name('api.login');
    Route::post('logout', [App\Http\Controllers\AuthController::class, 'logout'])
        ->middleware('auth:api')
        ->name('api.logout');
    Route::post('refresh', [App\Http\Controllers\AuthController::class, 'refresh'])->name('api.refresh');
    Route::post('register', [App\Http\Controllers\AuthController::class, 'register'])->name('api.register');

    /**
     * Email
     */
    Route::prefix('email')->group(function() {
        Route::post('confirm', [App\Http\Controllers\ConfirmEmailController::class, 'confirm'])
            ->name('api.confirm-email');
        Route::post('delete', [App\Http\Controllers\ConfirmEmailController::class, 'delete'])
            ->middleware('auth:api')
            ->name('api.delete-email-request');
        Route::post('resend', [App\Http\Controllers\ConfirmEmailController::class, 'resend'])
            ->middleware('auth:api')
            ->name('api.resend-email-request');
    });

    /**
     * Password
     */
    Route::prefix('password')->group(function() {
        Route::post('change', [App\Http\Controllers\ResetPasswordController::class, 'changePassword'])
            ->middleware('auth:api')
            ->name('api.change-password');
        Route::post('forgot', [App\Http\Controllers\ResetPasswordController::class, 'postEmail'])
            ->name('api.forgot-password');
        Route::post('reset', [App\Http\Controllers\ResetPasswordController::class, 'postReset'])
            ->name('api.reset-password');
    });
});

/**
 * Permissions
 */
Route::middleware(['auth:api', 'scopes:permissions:read', 'verified'])->prefix('permissions')->group(function () {
    Route::get('/', [App\Http\Controllers\PermissionController::class, 'all'])->name('api.get-permissions');
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
    Route::get('/', [App\Http\Controllers\RoleController::class, 'all'])->name('api.get-roles');
    Route::post('/', [App\Http\Controllers\RoleController::class, 'create'])
        ->middleware('scopes:roles:write')
        ->name('api.create-role');
    Route::delete('/', [App\Http\Controllers\RoleController::class, 'deleteBulk'])
        ->middleware('scopes:roles:delete')
        ->name('api.delete-roles');

    /**
     * Single role
     */
    Route::prefix('{id}')->where(['id' => '[0-9]+'])->group(function() {
        Route::get('/', [App\Http\Controllers\RoleController::class, 'role'])->name('api.get-role');
        Route::put('/', [App\Http\Controllers\RoleController::class, 'update'])
            ->middleware('scopes:roles:write')
            ->name('api.update-role');
        Route::delete('/', [App\Http\Controllers\RoleController::class, 'delete'])
            ->middleware('scopes:roles:delete')
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
    Route::get('/', [App\Http\Controllers\UserController::class, 'all'])->name('api.get-users');
    Route::post('/', [App\Http\Controllers\UserController::class, 'create'])
        ->middleware('scopes:users:write')
        ->name('api.create-user');
    Route::delete('/', [App\Http\Controllers\UserController::class, 'bulkDelete'])
        ->middleware('scopes:users:delete')
        ->name('api.delete-users');

    /**
     * Single user
     */
    Route::prefix('{id}')->where(['id' => '[0-9]+'])->group(function() {
        Route::delete('/', [App\Http\Controllers\UserController::class, 'delete'])
            ->middleware('scopes:users:delete')
            ->name('api.delete-user')->where('id', '[0-9]+');
    });

    /**
     * Trashed
     */
    Route::middleware('scopes:users:delete')->prefix('trashed')->group(function() {
        Route::get('/', [App\Http\Controllers\UserController::class, 'trashed'])
            ->name('api.get-trashed-users');
        Route::put('/', [App\Http\Controllers\UserController::class, 'restore'])
            ->name('api.restore-trashed-users');
        Route::delete('/', [App\Http\Controllers\UserController::class, 'deletePermanently'])
            ->name('api.delete-trashed-users');
    });
});
