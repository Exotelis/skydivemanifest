<?php

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
 * Aircraft
 */
Route::middleware(['auth:api', 'scopes:aircraft:read'])
    ->name('aircraft.')
    ->prefix('aircraft')
    ->group(function () {
        Route::get('/', [App\Http\Controllers\AircraftController::class, 'all'])->name('all');
        Route::post('/', [App\Http\Controllers\AircraftController::class, 'create'])
            ->middleware('scopes:aircraft:write')
            ->name('create');

        /**
         * Single aircraft
         */
        Route::prefix('{aircraft}')->group(function () {
            Route::get('/', [App\Http\Controllers\AircraftController::class, 'get'])->name('get');
            Route::put('/', [App\Http\Controllers\AircraftController::class, 'update'])
                ->middleware('scopes:aircraft:write')
                ->name('update');
            Route::put(
                '/put-back-into-service', [App\Http\Controllers\AircraftController::class, 'putBackIntoService']
            )->middleware('scopes:aircraft:delete')->name('back-into-service');
            Route::put('/put-out-of-service', [App\Http\Controllers\AircraftController::class, 'putOutOfService'])
                ->middleware('scopes:aircraft:delete')
                ->name('out-of-service');

            /**
             * Aircraft maintenance
             */
            Route::middleware(['scopes:aircraft_maintenance:read'])
                ->name('maintenance.')
                ->prefix('maintenance')
                ->group(function () {
                    Route::get('/', [App\Http\Controllers\AircraftMaintenanceController::class, 'all'])
                        ->name('all');
                    Route::post('/', [App\Http\Controllers\AircraftMaintenanceController::class, 'create'])
                        ->middleware('scopes:aircraft_maintenance:write')
                        ->name('create');
                    Route::delete( '/', [App\Http\Controllers\AircraftMaintenanceController::class, 'deleteBulk'])
                        ->middleware('scopes:aircraft_maintenance:delete')
                        ->name('deleteBulk');

                    /**
                     * Single aircraft maintenance
                     */
                    Route::prefix('{aircraftMaintenance}')->group(function () {
                        Route::get('/', [App\Http\Controllers\AircraftMaintenanceController::class, 'get'])
                            ->name('get');
                        Route::put('/', [App\Http\Controllers\AircraftMaintenanceController::class, 'update'])
                            ->middleware('scopes:aircraft_maintenance:write')
                            ->name('update');
                        Route::delete('/', [App\Http\Controllers\AircraftMaintenanceController::class, 'delete'])
                            ->middleware('scopes:aircraft_maintenance:delete')
                            ->name('delete');
                        Route::put(
                            '/complete',
                            [App\Http\Controllers\AircraftMaintenanceController::class, 'complete']
                        )->middleware('scopes:aircraft_maintenance:write')->name('complete');
                    });
            });
        });
    });

/**
 * Authentication
 */
Route::name('auth.')->prefix('auth')->group(function () {
    Route::post('/', [App\Http\Controllers\AuthController::class, 'login'])->name('login');
    Route::post('logout', [App\Http\Controllers\AuthController::class, 'logout'])
        ->middleware('auth:api')
        ->name('logout');
    Route::post('refresh', [App\Http\Controllers\AuthController::class, 'refresh'])->name('refresh');
    Route::post('register', [App\Http\Controllers\AuthController::class, 'register'])->name('register');

    /**
     * Email
     */
    Route::prefix('email')->group(function () {
        Route::post('confirm', [App\Http\Controllers\ConfirmEmailController::class, 'confirm'])
            ->name('confirm-email');
        Route::post('delete', [App\Http\Controllers\ConfirmEmailController::class, 'delete'])
            ->middleware('auth:api')
            ->name('delete-email-request');
        Route::post('resend', [App\Http\Controllers\ConfirmEmailController::class, 'resend'])
            ->middleware('auth:api')
            ->name('resend-email-request');
    });

    /**
     * Password
     */
    Route::prefix('password')->group(function () {
        Route::post('change', [App\Http\Controllers\ResetPasswordController::class, 'changePassword'])
            ->middleware('auth:api')
            ->name('change-password');
        Route::post('forgot', [App\Http\Controllers\ResetPasswordController::class, 'postEmail'])
            ->name('forgot-password');
        Route::post('reset', [App\Http\Controllers\ResetPasswordController::class, 'postReset'])
            ->name('reset-password');
    });

    /**
     * Recover
     */
    Route::post('recover', [App\Http\Controllers\AuthController::class, 'recover'])
        ->name('recover-user-with-token');

    /**
     * Tos
     */
    Route::post('tos', [App\Http\Controllers\AuthController::class, 'acceptTos'])
        ->middleware('auth:api')
        ->name('accept-tos');
});

/**
 * Countries
 */
Route::middleware(['auth:api', 'scopes:countries:read'])
    ->name('countries.')
    ->prefix('countries')
    ->group(function () {
        Route::get('/', [App\Http\Controllers\CountryController::class, 'all'])->name('all');
        Route::post('/', [App\Http\Controllers\CountryController::class, 'create'])
            ->middleware('scopes:countries:write')
            ->name('create');
        Route::delete('/', [App\Http\Controllers\CountryController::class, 'deleteBulk'])
            ->middleware('scopes:countries:delete')
            ->name('deleteBulk');

        /**
         * Single country
         */
        Route::prefix('{country}')->group(function () {
            Route::get('/', [App\Http\Controllers\CountryController::class, 'get'])->name('get');
            Route::put('/', [App\Http\Controllers\CountryController::class, 'update'])
                ->middleware('scopes:countries:write')
                ->name('update');
            Route::delete('/', [App\Http\Controllers\CountryController::class, 'delete'])
                ->middleware('scopes:countries:delete')
                ->name('delete');

            /**
             * Regions
             */
            Route::middleware(['scopes:regions:read'])
                ->name('regions.')
                ->prefix('regions')
                ->group(function () {
                    Route::get('/', [App\Http\Controllers\RegionController::class, 'all'])->name('all');
                    Route::post('/', [App\Http\Controllers\RegionController::class, 'create'])
                        ->middleware('scopes:regions:write')
                        ->name('create');
                    Route::delete('/', [App\Http\Controllers\RegionController::class, 'deleteBulk'])
                        ->middleware('scopes:regions:delete')
                        ->name('deleteBulk');

                    /**
                     * Single Region
                     */
                    Route::prefix('{region}')->group(function () {
                        Route::get('/', [App\Http\Controllers\RegionController::class, 'get'])->name('get');
                        Route::put('/', [App\Http\Controllers\RegionController::class, 'update'])
                            ->middleware('scopes:addresses:write')
                            ->name('update');
                        Route::delete('/', [App\Http\Controllers\RegionController::class, 'delete'])
                            ->middleware('scopes:addresses:delete')
                            ->name('delete');
                    });
                });
        });
    });

/**
 * Currencies
 */
Route::middleware(['auth:api', 'scopes:currencies:read'])
    ->name('currencies.')
    ->prefix('currencies')
    ->group(function () {
        Route::get('/', [App\Http\Controllers\CurrencyController::class, 'all'])->name('all');
        Route::post('/', [App\Http\Controllers\CurrencyController::class, 'create'])
            ->middleware('scopes:currencies:write')
            ->name('create');
        Route::delete('/', [App\Http\Controllers\CurrencyController::class, 'deleteBulk'])
            ->middleware('scopes:currencies:delete')
            ->name('deleteBulk');

        /**
         * Single currency
         */
        Route::prefix('{currency}')->group(function () {
            Route::get('/', [App\Http\Controllers\CurrencyController::class, 'get'])->name('get');
            Route::put('/', [App\Http\Controllers\CurrencyController::class, 'update'])
                ->middleware('scopes:currencies:write')
                ->name('update');
            Route::delete('/', [App\Http\Controllers\CurrencyController::class, 'delete'])
                ->middleware('scopes:currencies:delete')
                ->name('delete');
        });
    });

/**
 * Me/Personal
 */
Route::middleware(['auth:api'])->name('me.')->prefix('me')->group(function () {
    Route::get('/', [App\Http\Controllers\UserController::class, 'meGet'])->name('get');
    Route::put('/', [App\Http\Controllers\UserController::class, 'meUpdate'])->name('update');
    Route::delete('/', [App\Http\Controllers\UserController::class, 'meDelete'])->name('delete');

    /**
     * Addresses
     */
    Route::name('addresses.')->prefix('addresses')->group(function () {
        Route::get('/', [App\Http\Controllers\AddressController::class, 'meAll'])->name('all');
        Route::post('/', [App\Http\Controllers\AddressController::class, 'meCreate'])->name('create');
        Route::delete('/', [App\Http\Controllers\AddressController::class, 'meDeleteBulk'])
            ->name('deleteBulk');

        /**
         * Single Address
         */
        Route::prefix('{addressMe}')->group(function () {
            Route::get('/', [App\Http\Controllers\AddressController::class, 'meGet'])->name('get');
            Route::put('/', [App\Http\Controllers\AddressController::class, 'meUpdate'])->name('update');
            Route::delete('/', [App\Http\Controllers\AddressController::class, 'meDelete'])->name('delete');
        });
    });

    /**
     * Qualifications
     */
    Route::name('qualifications.')->prefix('qualifications')->group(function () {
        Route::get('/', [App\Http\Controllers\UserController::class, 'meQualificationsAll'])->name('all');
        Route::put('/', [App\Http\Controllers\UserController::class, 'meQualificationsUpdate'])
            ->name('update');
    });

    /**
     * Waivers
     */
    Route::name('waivers.')->prefix('waivers')->group(function () {
        Route::get('/', [App\Http\Controllers\UserWaiverController::class, 'meAll'])->name('all');

        /**
         * Single Waiver
         */
        Route::prefix('{waiverMeSignature}')->group(function () {
            Route::get('/', [App\Http\Controllers\UserWaiverController::class, 'meGet'])->name('get');
        });
    });
});

/**
 * Permissions
 */
Route::middleware(['auth:api', 'scopes:permissions:read'])
    ->name('permissions.')
    ->prefix('permissions')
    ->group(function () {
        Route::get('/', [App\Http\Controllers\PermissionController::class, 'all'])->name('all');
    });

/**
 * Qualifications
 */
Route::middleware(['auth:api', 'scopes:qualifications:read'])
    ->name('qualifications.')
    ->prefix('qualifications')
    ->group(function () {
        Route::get('/', [App\Http\Controllers\QualificationController::class, 'all'])->name('all');
        Route::post('/', [App\Http\Controllers\QualificationController::class, 'create'])
            ->middleware('scopes:qualifications:write')
            ->name('create');
        Route::delete('/', [App\Http\Controllers\QualificationController::class, 'deleteBulk'])
            ->middleware('scopes:qualifications:delete')
            ->name('deleteBulk');

        /**
         * Single qualifications
         */
        Route::prefix('{qualification}')->group(function () {
            Route::get('/', [App\Http\Controllers\QualificationController::class, 'get'])->name('get');
            Route::put('/', [App\Http\Controllers\QualificationController::class, 'update'])
                ->middleware('scopes:qualifications:write')
                ->name('update');
            Route::delete('/', [App\Http\Controllers\QualificationController::class, 'delete'])
                ->middleware('scopes:qualifications:delete')
                ->name('delete');
        });
    });

/**
 * Roles
 */
Route::middleware(['auth:api', 'scopes:roles:read'])->name('roles.')->prefix('roles')->group(function () {
    Route::get('/', [App\Http\Controllers\RoleController::class, 'all'])->name('all');
    Route::post('/', [App\Http\Controllers\RoleController::class, 'create'])
        ->middleware('scopes:roles:write')
        ->name('create');
    Route::delete('/', [App\Http\Controllers\RoleController::class, 'deleteBulk'])
        ->middleware('scopes:roles:delete')
        ->name('deleteBulk');

    /**
     * Single role
     */
    Route::prefix('{role}')->where(['role' => '[0-9]+'])->group(function () {
        Route::get('/', [App\Http\Controllers\RoleController::class, 'role'])->name('get');
        Route::put('/', [App\Http\Controllers\RoleController::class, 'update'])
            ->middleware('scopes:roles:write')
            ->name('update');
        Route::delete('/', [App\Http\Controllers\RoleController::class, 'delete'])
            ->middleware('scopes:roles:delete')
            ->name('delete');
        // TODO - add/{roleID}/users to get a list of users or add/remove them from the role
    });
});

/**
 * Roles names
 */
Route::middleware(['auth:api', 'scope:roles:read,users:read'])->get('roles/names', function () {
    return \App\Models\Role::all()->pluck('name')->toArray();
})->name('roles.names');

/**
 * Roles valid
 */
Route::middleware(['auth:api', 'scope:roles:read,users:read'])->get('roles/valid', function () {
    return validRoles(auth()->user());
})->name('roles.valid');

/**
 * Timezones
 */
Route::get('timezones', function () {
    return \Carbon\CarbonTimeZone::listIdentifiers();
})->name('timezones');

/**
 * Tos - Terms of Service
 */
Route::get('tos', function () {
    return __('tos');
})->name('tos');

/**
 * Unassigned signed waivers
 */
Route::middleware(['auth:api', 'scopes:unassigned_waivers:read'])
    ->name('unassigned-waivers.')
    ->prefix('unassigned-waivers')
    ->group(function () {
        Route::get('/', [App\Http\Controllers\UnassignedWaiverController::class, 'all'])
            ->name('all');
        Route::delete('/', [App\Http\Controllers\UnassignedWaiverController::class, 'deleteBulk'])
            ->middleware('scopes:unassigned_waivers:delete')
            ->name('deleteBulk');

        /**
         * Single unassigned waiver
         */
        Route::prefix('{unassignedWaiver}')->where(['unassignedWaiver' => '[0-9]+'])->group(function () {
            Route::get('/', [App\Http\Controllers\UnassignedWaiverController::class, 'get'])->name('get');
            Route::delete('/', [App\Http\Controllers\UnassignedWaiverController::class, 'delete'])
                ->middleware('scopes:unassigned_waivers:delete')
                ->name('delete');
            Route::post('/assign', [App\Http\Controllers\UnassignedWaiverController::class, 'assign'])
                ->middleware('scopes:unassigned_waivers:write')
                ->name('assign');
        });
    });

/**
 * Users
 */
Route::middleware(['auth:api', 'scopes:users:read'])->name('users.')->prefix('users')->group(function () {
    Route::get('/', [App\Http\Controllers\UserController::class, 'all'])->name('all');
    Route::post('/', [App\Http\Controllers\UserController::class, 'create'])
        ->middleware('scopes:users:write')
        ->name('create');
    Route::delete('/', [App\Http\Controllers\UserController::class, 'deleteBulk'])
        ->middleware('scopes:users:delete')
        ->name('deleteBulk');

    /**
     * Restore user
     */
    Route::prefix('{userDeleted}')->where(['user' => '[0-9]+'])->group(function () {
        Route::post('restore', [App\Http\Controllers\UserController::class, 'restore'])
            ->middleware('scopes:users:delete')
            ->name('restore');
    });

    /**
     * Single user
     */
    Route::prefix('{user}')->where(['user' => '[0-9]+'])->group(function () {
        Route::get('/', [App\Http\Controllers\UserController::class, 'get'])->name('get');
        Route::put('/', [App\Http\Controllers\UserController::class, 'update'])
            ->middleware('scopes:users:write')
            ->name('update');
        Route::delete('/', [App\Http\Controllers\UserController::class, 'delete'])
            ->middleware('scopes:users:delete')
            ->name('delete');

        /**
         * Addresses
         */
        Route::middleware(['scopes:addresses:read'])
            ->name('addresses.')
            ->prefix('addresses')
            ->group(function () {
                Route::get('/', [App\Http\Controllers\AddressController::class, 'all'])->name('all');
                Route::post('/', [App\Http\Controllers\AddressController::class, 'create'])
                    ->middleware('scopes:addresses:write')
                    ->name('create');
                Route::delete('/', [App\Http\Controllers\AddressController::class, 'deleteBulk'])
                    ->middleware('scopes:addresses:delete')
                    ->name('deleteBulk');

                /**
                 * Single Address
                 */
                Route::prefix('{address}')->group(function () {
                    Route::get('/', [App\Http\Controllers\AddressController::class, 'get'])->name('get');
                    Route::put('/', [App\Http\Controllers\AddressController::class, 'update'])
                        ->middleware('scopes:addresses:write')
                        ->name('update');
                    Route::delete('/', [App\Http\Controllers\AddressController::class, 'delete'])
                        ->middleware('scopes:addresses:delete')
                        ->name('delete');
                });
            });

        /**
         * Qualifications
         */
        Route::middleware(['scopes:qualifications:read'])
            ->name('qualifications.')
            ->prefix('qualifications')
            ->group(function () {
                Route::get('/', [App\Http\Controllers\UserController::class, 'qualificationsAll'])
                    ->name('all');
                Route::put('/', [App\Http\Controllers\UserController::class, 'qualificationsUpdate'])
                    ->middleware('scopes:qualifications:write')
                    ->name('update');
            });

        /**
         * Waivers
         */
        Route::middleware(['scopes:waivers:read'])
            ->name('waivers.')
            ->prefix('waivers')
            ->group(function () {
                Route::get('/', [App\Http\Controllers\UserWaiverController::class, 'all'])->name('all');
                Route::delete('/', [App\Http\Controllers\UserWaiverController::class, 'deleteBulk'])
                    ->middleware('scopes:waivers:delete')
                    ->name('deleteBulk');

                /**
                 * Single Waiver
                 */
                Route::prefix('{waiverSignature}')->group(function () {
                    Route::get('/', [App\Http\Controllers\UserWaiverController::class, 'get'])->name('get');
                    Route::delete('/', [App\Http\Controllers\UserWaiverController::class, 'delete'])
                        ->middleware('scopes:waivers:delete')
                        ->name('delete');
                });
            });
    });

    /**
     * Trashed
     */
    Route::middleware('scopes:users:delete')
        ->name('trashed.')
        ->prefix('trashed')
        ->group(function () {
            Route::get('/', [App\Http\Controllers\UserController::class, 'trashed'])->name('get');
            Route::put('/', [App\Http\Controllers\UserController::class, 'restoreBulk'])->name('restoreBulk');
            Route::delete('/', [App\Http\Controllers\UserController::class, 'deletePermanently'])
                ->name('deletePermanently');
        });
});

/**
 * Waivers
 */
Route::middleware(['auth:api'])->name('waivers.')->prefix('waivers')->group(function () {
    Route::name('active.')->prefix('active')->group(function () {
        Route::get('/', [App\Http\Controllers\WaiverController::class, 'activeAll'])
            ->name('all');

        /**
         * Single active
         */
        Route::prefix('{waiverActive}')->where(['waiverActive' => '[0-9]+'])->group(function() {
            Route::get('/', [App\Http\Controllers\WaiverController::class, 'activeGet'])
                ->name('get');
            Route::post('/sign', [App\Http\Controllers\WaiverController::class, 'activeSign'])
                ->name('sign');
            Route::delete('/withdraw', [App\Http\Controllers\WaiverController::class, 'activeWithdraw'])
                ->name('withdraw');
        });
    });
    Route::get('/', [App\Http\Controllers\WaiverController::class, 'all'])
        ->middleware('scopes:waivers:read')
        ->name('all');
    Route::post('/', [App\Http\Controllers\WaiverController::class, 'create'])
        ->middleware('scopes:waivers:write')
        ->name('create');
    Route::delete('/', [App\Http\Controllers\WaiverController::class, 'deleteBulk'])
        ->middleware('scopes:waivers:delete')
        ->name('deleteBulk');

    /**
     * Single waiver
     */
    Route::middleware(['scopes:waivers:read'])
        ->prefix('{waiver}')
        ->where(['waiver' => '[0-9]+'])
        ->group(function () {
            Route::get('/', [App\Http\Controllers\WaiverController::class, 'get'])->name('get');
            Route::put('/', [App\Http\Controllers\WaiverController::class, 'update'])
                ->middleware('scopes:waivers:write')
                ->name('update');
            Route::delete('/', [App\Http\Controllers\WaiverController::class, 'delete'])
                ->middleware('scopes:waivers:delete')
                ->name('delete');
            Route::post('/duplicate', [App\Http\Controllers\WaiverController::class, 'duplicate'])
                ->middleware('scopes:waivers:write')
                ->name('duplicate');

            /**
             * Texts
             */
            Route::name('texts.')->prefix('texts')->group(function () {
                Route::get('/', [App\Http\Controllers\TextController::class, 'all'])->name('all');
                Route::post('/', [App\Http\Controllers\TextController::class, 'create'])
                    ->middleware('scopes:waivers:write')
                    ->name('create');
                Route::delete('/', [App\Http\Controllers\TextController::class, 'deleteBulk'])
                    ->middleware('scopes:waivers:delete')
                    ->name('deleteBulk');

                /**
                 * Single waiver text
                 */
                Route::prefix('{waiverText}')->group(function () {
                    Route::get('/', [App\Http\Controllers\TextController::class, 'get'])->name('get');
                    Route::put('/', [App\Http\Controllers\TextController::class, 'update'])
                        ->middleware('scopes:waivers:write')
                        ->name('update');
                    Route::delete('/', [App\Http\Controllers\TextController::class, 'delete'])
                        ->middleware('scopes:waivers:delete')
                        ->name('delete');
                });
            });
        });
});
