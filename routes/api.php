<?php
declare(strict_types=1);

Route::middleware('auth:web')->domain('auth.'.env('APP_DOMAIN'))->group(function () {
    Route::prefix('user')->group(function () {
        Route::get('/info', 'Auth\API\UserController@info');
        Route::prefix('permissions')->group(function () {
            Route::get('/accessSALOP', 'Auth\API\UserController@permissionsAccessSALOP');
            Route::get('/accessGOI', 'Auth\API\UserController@permissionsAccessGOI');
            Route::get('/accessCOVID19', 'Auth\API\UserController@permissionsAccessCOVID19');
            Route::get('/isManager', 'Auth\API\UserController@permissionsIsManager');
            Route::get('/isAdmin', 'Auth\API\UserController@permissionsIsAdmin');
        });
        Route::get('/extensions', 'Auth\API\UserController@extensions');
    });
    Route::prefix('users')->middleware(['can:isManager'])->group(function () {
        Route::get('/', 'Auth\API\UsersController@all');
        Route::prefix('{id}')->where(['id', '[0-9]+'])->group(function () {
            Route::post('/permissions', 'Auth\API\UsersController@editPermissions');
        });
    });
});

Route::middleware('auth:web')->domain('salop.'.env('APP_DOMAIN'))->group(function () {
    Route::middleware(['can:accessSALOP'])->prefix('door')->group(function () {
        Route::get('/open', 'SALOP\API\DoorController@open');
    });
    Route::prefix('users')->middleware(['can:isManager'])->group(function () {
        Route::prefix('{id}')->where(['id', '[0-9]+'])->group(function () {
            Route::post('/extensions', 'SALOP\API\UsersController@editExtensions');
        });
    });
    Route::prefix('extensions')->group(function () {
        Route::middleware(['can:isManager'])->get('/numbers', 'SALOP\API\ExtensionsController@numbers');
        Route::middleware(['can:isAdmin'])->get('/full', 'SALOP\API\ExtensionsController@full');
    });
});


Route::middleware('auth:web')->domain('covid19.'.env('APP_DOMAIN'))->group(function () {
    Route::middleware(['can:accessCOVID19Callbacks'])->prefix('callbacks')->group(function () {
        Route::get('/', 'COVID19\API\CallbackController@callbacks');
    });
});
