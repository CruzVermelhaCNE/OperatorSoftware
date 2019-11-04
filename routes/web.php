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

Route::get('/', function () {
    return view('login');
});

Route::prefix('panel')->name('panel.')->group(function () {
    Route::get('/', function () {
        return view('fop2');
    })->name('fop2');
    Route::get('/missed_calls', function () {
        return view('missed_calls');
    })->name('missed_calls');
    Route::get('/callbacks', function () {
        return view('callbacks');
    })->name('callbacks');
});


Route::prefix('data')->name('data.')->group(function () {
    Route::get('missed_calls.json', 'CDRMissedCallsController@fetch')->name('missed_calls');
    Route::get('callbacks.json', 'CDRBusyCallsController@fetch')->name('callbacks');
});
