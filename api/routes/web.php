<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Render Mail
/*
Route::get('/mail', function () {
    $user = \App\Models\User::find(1);
    $locale = 'de';

    $parameter1 = 'qq09tq30rgoiq';

    return (new App\Mail\SoftDeleteUser($user, $parameter1))->locale($locale);
});
*/

// Automatic redirect
// Route::redirect('/', '/en');

Route::prefix('{language}')->where(['language' => \implode('|', validLocales())])->group(function () {
    /**
     * Waivers
     */
    Route::name('e-waivers.')->prefix('e-waivers/{waiverID?}')->group(function () {
        Route::get('/', [App\Http\Controllers\WaiverController::class, 'eWaiversGet'])
            ->where(['waiverID' => '[0-9]+'])
            ->name('get');
        Route::post('/sign', [App\Http\Controllers\WaiverController::class, 'eWaiverSign'])
            ->middleware('throttle:10,1')
            ->name('sign');
    });
});

/**
 * Render either welcome page or spa
 */
Route::get('/{any}', function () {
    return view()->first(['spa', 'welcome']);
})->where('any', '.*');
