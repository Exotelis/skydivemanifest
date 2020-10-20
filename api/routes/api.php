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

    /**
     * Recover
     */
    Route::post('recover', [App\Http\Controllers\AuthController::class, 'recover'])
        ->name('api.recover-user-with-token');

    /**
     * Tos
     */
    Route::post('tos', [App\Http\Controllers\AuthController::class, 'acceptTos'])
        ->middleware('auth:api')
        ->name('api.accept-tos');
});

/**
 * Countries
 */
Route::middleware(['auth:api', 'scopes:countries:read'])->prefix('countries')->group(function () {
    Route::get('/', [App\Http\Controllers\CountryController::class, 'all'])->name('api.get-countries');
    Route::post('/', [App\Http\Controllers\CountryController::class, 'create'])
        ->middleware('scopes:countries:write')
        ->name('api.create-country');
    Route::delete('/', [App\Http\Controllers\CountryController::class, 'deleteBulk'])
        ->middleware('scopes:countries:delete')
        ->name('api.delete-countries');

    /**
     * Single country
     */
    Route::prefix('{countryID}')->where(['countryID' => '[0-9]+'])->group(function() {
        Route::get('/', [App\Http\Controllers\CountryController::class, 'get'])->name('api.get-country');
        Route::put('/', [App\Http\Controllers\CountryController::class, 'update'])
            ->middleware('scopes:countries:write')
            ->name('api.update-country');
        Route::delete('/', [App\Http\Controllers\CountryController::class, 'delete'])
            ->middleware('scopes:countries:delete')
            ->name('api.delete-country');
    });
});

/**
 * Currencies
 */
Route::middleware(['auth:api', 'scopes:currencies:read'])->prefix('currencies')->group(function () {
    Route::get('/', [App\Http\Controllers\CurrencyController::class, 'all'])->name('api.get-currencies');
    Route::post('/', [App\Http\Controllers\CurrencyController::class, 'create'])
        ->middleware('scopes:currencies:write')
        ->name('api.create-currency');
    Route::delete('/', [App\Http\Controllers\CurrencyController::class, 'deleteBulk'])
        ->middleware('scopes:currencies:delete')
        ->name('api.delete-currencies');

    /**
     * Single currency
     */
    Route::prefix('{currencyCode}')->where(['currencyCode' => '[A-Za-z0-9]{3}'])->group(function() {
        Route::get('/', [App\Http\Controllers\CurrencyController::class, 'get'])->name('api.get-currency');
        Route::put('/', [App\Http\Controllers\CurrencyController::class, 'update'])
            ->middleware('scopes:currencies:write')
            ->name('api.update-currency');
        Route::delete('/', [App\Http\Controllers\CurrencyController::class, 'delete'])
            ->middleware('scopes:currencies:delete')
            ->name('api.delete-currency');
    });
});

/**
 * Me/Personal
 */
Route::middleware(['auth:api'])->prefix('me')->group(function () {
    Route::get('/', [App\Http\Controllers\UserController::class, 'meGet']);
    Route::put('/', [App\Http\Controllers\UserController::class, 'meUpdate']);
    Route::delete('/', [App\Http\Controllers\UserController::class, 'meDelete']);

    /**
     * Addresses
     */
    Route::prefix('addresses')->group(function() {
        Route::get('/', [App\Http\Controllers\AddressController::class, 'meAll']);
        Route::post('/', [App\Http\Controllers\AddressController::class, 'meCreate']);
        Route::delete('/', [App\Http\Controllers\AddressController::class, 'meDeleteBulk']);

        /**
         * Single Address
         */
        Route::prefix('{addressID}')->where(['addressID' => '[0-9]+'])->group(function() {
            Route::get('/', [App\Http\Controllers\AddressController::class, 'meGet']);
            Route::put('/', [App\Http\Controllers\AddressController::class, 'meUpdate']);
            Route::delete('/', [App\Http\Controllers\AddressController::class, 'meDelete']);
        });
    });
});

/**
 * Permissions
 */
Route::middleware(['auth:api', 'scopes:permissions:read'])->prefix('permissions')->group(function () {
    Route::get('/', [App\Http\Controllers\PermissionController::class, 'all'])->name('api.get-permissions');
});

/**
 * Roles
 */
Route::middleware(['auth:api', 'scopes:roles:read'])->prefix('roles')->group(function () {
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
    Route::prefix('{roleID}')->where(['roleID' => '[0-9]+'])->group(function() {
        Route::get('/', [App\Http\Controllers\RoleController::class, 'role'])->name('api.get-role');
        Route::put('/', [App\Http\Controllers\RoleController::class, 'update'])
            ->middleware('scopes:roles:write')
            ->name('api.update-role');
        Route::delete('/', [App\Http\Controllers\RoleController::class, 'delete'])
            ->middleware('scopes:roles:delete')
            ->name('api.delete-role');
        // TODO - add/{roleID}/users to get a list of users or add/remove them from the role
    });
});

/**
 * Roles names
 */
Route::middleware(['auth:api', 'scope:roles:read,users:read'])->get('roles/names', function() {
    return \App\Models\Role::all()->pluck('name')->toArray();
})->name('api.get-roles-names');

/**
 * Roles valid
 */
Route::middleware(['auth:api', 'scope:roles:read,users:read'])->get('roles/valid', function() {
    return validRoles(auth()->user());
})->name('api.get-roles-valid');

/**
 * Timezones
 */
Route::get('timezones', function() {
    return \Carbon\CarbonTimeZone::listIdentifiers();
})->name('api.timezones');

/**
 * Tos - Terms of Service
 */
Route::get('tos', function() {
    return __('tos');
})->name('api.tos');

/**
 * Users
 */
Route::middleware(['auth:api', 'scopes:users:read'])->prefix('users')->group(function() {
    Route::get('/', [App\Http\Controllers\UserController::class, 'all'])->name('api.get-users');
    Route::post('/', [App\Http\Controllers\UserController::class, 'create'])
        ->middleware('scopes:users:write')
        ->name('api.create-user');
    Route::delete('/', [App\Http\Controllers\UserController::class, 'deleteBulk'])
        ->middleware('scopes:users:delete')
        ->name('api.delete-users');

    /**
     * Single user
     */
    Route::prefix('{userID}')->where(['userID' => '[0-9]+'])->group(function() {
        Route::get('/', [App\Http\Controllers\UserController::class, 'get'])->name('api.get-user');
        Route::put('/', [App\Http\Controllers\UserController::class, 'update'])
            ->middleware('scopes:users:write')
            ->name('api.update-user');
        Route::delete('/', [App\Http\Controllers\UserController::class, 'delete'])
            ->middleware('scopes:users:delete')
            ->name('api.delete-user');
        Route::post('restore', [App\Http\Controllers\UserController::class, 'restore'])
            ->middleware('scopes:users:delete')
            ->name('api.restore-trashed-user');

        /**
         * Addresses
         */
        Route::middleware(['scopes:addresses:read'])->prefix('addresses')->group(function() {
            Route::get('/', [App\Http\Controllers\AddressController::class, 'all'])
                ->name('api.get-addresses');
            Route::post('/', [App\Http\Controllers\AddressController::class, 'create'])
                ->middleware('scopes:addresses:write')
                ->name('api.create-address');
            Route::delete('/', [App\Http\Controllers\AddressController::class, 'deleteBulk'])
                ->middleware('scopes:addresses:delete')
                ->name('api.delete-addresses');

            /**
             * Single Address
             */
            Route::prefix('{addressID}')->where(['addressID' => '[0-9]+'])->group(function() {
                Route::get('/', [App\Http\Controllers\AddressController::class, 'get'])
                    ->name('api.get-address');
                Route::put('/', [App\Http\Controllers\AddressController::class, 'update'])
                    ->middleware('scopes:addresses:write')
                    ->name('api.update-address');
                Route::delete('/', [App\Http\Controllers\AddressController::class, 'delete'])
                    ->middleware('scopes:addresses:delete')
                    ->name('api.delete-address');
            });
        });
    });

    /**
     * Trashed
     */
    Route::middleware('scopes:users:delete')->prefix('trashed')->group(function() {
        Route::get('/', [App\Http\Controllers\UserController::class, 'trashed'])
            ->name('api.get-trashed-users');
        Route::put('/', [App\Http\Controllers\UserController::class, 'restoreBulk'])
            ->name('api.restore-trashed-users');
        Route::delete('/', [App\Http\Controllers\UserController::class, 'deletePermanently'])
            ->name('api.delete-trashed-users');
    });
});
