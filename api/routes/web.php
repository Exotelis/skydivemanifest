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

Route::get('/{any}', function () {
    return view()->first(['spa', 'welcome']);
})->where('any', '^(?!api)(?!mail).*$');

// Render Mail
/*
Route::get('/mail', function () {
    $user = \App\Models\User::find(1);
    $locale = 'de';

    $parameter1 = 'qq09tq30rgoiq';

    return (new App\Mail\SoftDeleteUser($user, $parameter1))->locale($locale);
});
*/
