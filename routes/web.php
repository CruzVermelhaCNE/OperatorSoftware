<?php
declare(strict_types=1);

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

Auth::routes([
    'register' => false, 
    'reset' => false, 
    'confirm' => false, 
    'verify' => false
]);

Route::get('/', function () {
    return view('login');
})->name("homepage");

Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout')->name("logout");

Route::prefix('panel')->name('panel.')->middleware('auth')->group(function () {

    Route::get('/', function () {
        return view('fop2');
    })->name('fop2');

    Route::get('/missed_calls', function () {
        return view('missed_calls');
    })->name('missed_calls');

    Route::get('/callbacks', function () {
        return view('callbacks');
    })->name('callbacks');

    Route::get('/change_password', function () {
        return view('change_password');
    })->name('change_password');
    Route::post('/change_password', 'Auth\ChangePasswordController@changePassword')->name('change_password');

    Route::get('users', 'ManagementController@users')->name('users');
    Route::get('reports', 'ManagementController@reports')->name('reports');

    Route::get('extensions', 'AdministrationController@extensions')->name('extensions');
});


Route::prefix('data')->name('data.')->middleware('auth')->group(function () {
    Route::get('missed_calls.json', 'CDRMissedCallsController@fetch')->name('missed_calls');
    Route::get('callbacks.json', 'CDRBusyCallsController@fetch')->name('callbacks');
});
