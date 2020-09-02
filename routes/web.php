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

Route::domain('auth.'.env('APP_DOMAIN'))->name('auth.')->group(function () {
    Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
    Route::get('login/microsoft', 'Auth\LoginController@redirectToProvider')->name('microsoft');
    Route::get('login/microsoft/callback', 'Auth\LoginController@handleProviderCallback');
    Route::middleware('auth')->group(function () {
        Route::get('/', 'Auth\LoginController@index')->name('index');
        Route::get('/logout', 'Auth\LoginController@logout')->name('logout');
    });
});


Route::domain('salop.'.env('APP_DOMAIN'))->middleware(['auth'])->name('salop.')->group(function () {
    Route::get('/', function () {
        return view('fop2');
    })->name('fop2');

    Route::get('/missed_calls', function () {
        return view('missed_calls');
    })->name('missed_calls');

    Route::get('/callbacks', function () {
        return view('callbacks');
    })->name('callbacks');

    Route::get('/door_opener', function () {
        return view('door_opener');
    })->name('door_opener');

    Route::get('users', 'ManagementController@users')->name('users');
    Route::get('reports', 'ManagementController@reports')->name('reports');
    Route::get('extensions', 'AdministrationController@extensions')->name('extensions');

    Route::prefix('data')->name('data.')->group(function () {
        Route::get('missed_calls.json', 'CDRMissedCallsController@fetch')->name('missed_calls');
        Route::get('callbacks.json', 'CDRBusyCallsController@fetch')->name('callbacks');
    });

    Route::prefix('actions')->name('actions.')->group(function () {
        Route::get('open_door', 'GDSAPI@openDoor')->name('open_door');
    });
});

Route::domain('goi.'.env('APP_DOMAIN'))->middleware(['auth'])->name('theaters_of_operations.')->group(function () {
    Route::get('/', 'TheatersOfOperationsPanelController@index')->name('index');
    Route::get('map', 'TheatersOfOperationsPanelController@map')->name('map');
    Route::prefix('timetape')->name('timetape.')->group(function () {
        Route::get('/', 'TheatersOfOperationsPanelController@timetape')->name('index');
        Route::get('all', 'TheatersOfOperations\TimeTapeController@all')->name('all');
        Route::get('to/{id}', 'TheatersOfOperations\TimeTapeController@to')->where(['id', '[0-9]+'])->name('to');
        Route::get('poi/{id}', 'TheatersOfOperations\TimeTapeController@poi')->where(['id', '[0-9]+'])->name('poi');
        Route::get('event/{id}', 'TheatersOfOperations\TimeTapeController@event')->where(['id', '[0-9]+'])->name('event');
        Route::get('unit/{id}', 'TheatersOfOperations\TimeTapeController@unit')->where(['id', '[0-9]+'])->name('unit');
        Route::get('crew/{id}', 'TheatersOfOperations\TimeTapeController@crew')->where(['id', '[0-9]+'])->name('crew');
        Route::prefix('objects')->name('objects.')->group(function () {
            Route::get('to', 'TheatersOfOperations\TimeTapeController@to_objects')->name('to');
            Route::get('poi', 'TheatersOfOperations\TimeTapeController@poi_objects')->name('poi');
            Route::get('event', 'TheatersOfOperations\TimeTapeController@event_objects')->name('event');
            Route::get('unit', 'TheatersOfOperations\TimeTapeController@unit_objects')->name('unit');
            Route::get('crew', 'TheatersOfOperations\TimeTapeController@crew_objects')->name('crew');
        });
    });



    Route::get('list', 'TheatersOfOperationsPanelController@list')->name('list');
    Route::get('getActive', 'TheatersOfOperations\TheaterOfOperationsController@getActive')->name('getActive');
    Route::get('getConcluded', 'TheatersOfOperations\TheaterOfOperationsController@getConcluded')->name('getConcluded');

    Route::get('create', 'TheatersOfOperationsPanelController@create')->name('create');
    Route::post('create', 'TheatersOfOperations\TheaterOfOperationsController@create');

    Route::get('info', 'TheatersOfOperations\TheaterOfOperationsController@info')->name('info');
    Route::get('units_info', 'TheatersOfOperations\TheaterOfOperationsController@units_info')->name('units_info');
    Route::get('events_info', 'TheatersOfOperations\TheaterOfOperationsController@events_info')->name('events_info');
    Route::get('pois_info', 'TheatersOfOperations\TheaterOfOperationsController@pois_info')->name('pois_info');
    Route::get('unit/{unit_id}', 'TheatersOfOperations\TheaterOfOperationsController@unit_redirect')->where(['unit_id', '[0-9]+']);
    Route::get('event/{event_id}', 'TheatersOfOperations\TheaterOfOperationsController@event_redirect')->where(['event_id', '[0-9]+']);
    Route::get('poi/{poi_id}', 'TheatersOfOperations\TheaterOfOperationsController@poi_redirect')->where(['poi_id', '[0-9]+']);



    Route::get('{id}/', 'TheatersOfOperations\TheaterOfOperationsController@single')->where(['id', '[0-9]+'])->name('single');
    Route::get('{id}/edit', 'TheatersOfOperations\TheaterOfOperationsController@edit')->where(['id', '[0-9]+'])->name('edit');
    Route::post('{id}/edit', 'TheatersOfOperations\TheaterOfOperationsController@postEdit')->where(['id', '[0-9]+']);
    Route::post('{id}/addToTimeTape', 'TheatersOfOperations\TheaterOfOperationsController@addToTimeTape')->where(['id', '[0-9]+'])->name('addToTimeTape');
    Route::get('{id}/close', 'TheatersOfOperations\TheaterOfOperationsController@close')->where(['id', '[0-9]+'])->name('close');
    Route::get('{id}/reopen', 'TheatersOfOperations\TheaterOfOperationsController@reopen')->where(['id', '[0-9]+'])->name('reopen');
    Route::get('{id}/getBriefTimeTape', 'TheatersOfOperations\TheaterOfOperationsController@getBriefTimeTape')->where(['id', '[0-9]+'])->name('getBriefTimeTape');
    Route::get('{id}/getCoordination', 'TheatersOfOperations\TheaterOfOperationsController@getCoordination')->where(['id', '[0-9]+'])->name('getCoordination');
    Route::get('{id}/getPOIs', 'TheatersOfOperations\TheaterOfOperationsController@getPOIs')->where(['id', '[0-9]+'])->name('getPOIs');
    Route::get('{id}/getEvents', 'TheatersOfOperations\TheaterOfOperationsController@getEvents')->where(['id', '[0-9]+'])->name('getEvents');
    Route::get('{id}/getActiveEvents', 'TheatersOfOperations\TheaterOfOperationsController@getActiveEvents')->where(['id', '[0-9]+'])->name('getActiveEvents');
    Route::get('{id}/getUnits', 'TheatersOfOperations\TheaterOfOperationsController@getUnits')->where(['id', '[0-9]+'])->name('getUnits');
    Route::get('{id}/getActiveUnits', 'TheatersOfOperations\TheaterOfOperationsController@getActiveUnits')->where(['id', '[0-9]+'])->name('getActiveUnits');
    Route::get('{id}/getCrews', 'TheatersOfOperations\TheaterOfOperationsController@getCrews')->where(['id', '[0-9]+'])->name('getCrews');
    Route::get('{id}/getActiveCrews', 'TheatersOfOperations\TheaterOfOperationsController@getActiveCrews')->where(['id', '[0-9]+'])->name('getActiveCrews');
    Route::get('{id}/getCommunicationChannels', 'TheatersOfOperations\TheaterOfOperationsController@getCommunicationChannels')->where(['id', '[0-9]+'])->name('getCommunicationChannels');
    Route::post('{id}/updateObservations', 'TheatersOfOperations\TheaterOfOperationsController@updateObservations')->where(['id', '[0-9]+'])->name('updateObservations');

    Route::prefix('{id}/objects')->where(['id', '[0-9]+'])->name('objects.')->group(function () {
        Route::get('poi', 'TheatersOfOperations\TheaterOfOperationsController@poi_objects')->name('poi');
        Route::get('event', 'TheatersOfOperations\TheaterOfOperationsController@event_objects')->name('event');
        Route::get('unit', 'TheatersOfOperations\TheaterOfOperationsController@unit_objects')->name('unit');
        Route::get('crew', 'TheatersOfOperations\TheaterOfOperationsController@crew_objects')->name('crew');
    });

    /*Route::prefix('crews')->name('crews.')->group(function () {
        Route::get('/', 'TheatersOfOperations\CrewsController@index')->name('index');
    });

    Route::prefix('units')->name('units.')->group(function () {
        Route::get('/', 'TheatersOfOperations\CrewsController@index')->name('index');
    });*/

    Route::prefix('{id}/coordination')->where(['id', '[0-9]+'])->name('coordination.')->group(function () {
        Route::get('create', 'TheatersOfOperations\TheaterOfOperationsController@createCoordination')->name('create');
        Route::post('create', 'TheatersOfOperations\CoordinationController@create');
        Route::get('edit/{coordination_id}', 'TheatersOfOperations\TheaterOfOperationsController@editCoordination')->where(['coordination_id', '[0-9]+'])->name('edit');
        Route::post('edit/{coordination_id}', 'TheatersOfOperations\CoordinationController@edit')->where(['coordination_id', '[0-9]+']);
        Route::get('remove/{coordination_id}', 'TheatersOfOperations\CoordinationController@remove')->where(['coordination_id', '[0-9]+'])->name('remove');
    });

    Route::prefix('{id}/pois')->where(['id', '[0-9]+'])->name('pois.')->group(function () {
        Route::get('create', 'TheatersOfOperations\TheaterOfOperationsController@createPOI')->name('create');
        Route::post('create', 'TheatersOfOperations\POIsController@create');
        Route::get('{poi_id}/edit/', 'TheatersOfOperations\TheaterOfOperationsController@editPOI')->where(['poi_id', '[0-9]+'])->name('edit');
        Route::post('{poi_id}/edit/', 'TheatersOfOperations\POIsController@edit')->where(['poi_id', '[0-9]+']);
        Route::get('{poi_id}/remove/', 'TheatersOfOperations\POIsController@remove')->where(['poi_id', '[0-9]+'])->name('remove');
    });

    Route::prefix('{id}/events')->where(['id', '[0-9]+'])->name('events.')->group(function () {
        Route::get('create', 'TheatersOfOperations\TheaterOfOperationsController@createEvent')->name('create');
        Route::post('create', 'TheatersOfOperations\EventsController@create');

        Route::get('{event_id}/', 'TheatersOfOperations\EventsController@single')->where(['event_id', '[0-9]+'])->name('single');
        Route::get('{event_id}/getBriefTimeTape', 'TheatersOfOperations\EventsController@getBriefTimeTape')->where(['event_id', '[0-9]+'])->name('getBriefTimeTape');
        Route::get('{event_id}/getVictims', 'TheatersOfOperations\EventsController@getVictims')->where(['event_id', '[0-9]+'])->name('getVictims');
        Route::get('{event_id}/getUnits', 'TheatersOfOperations\EventsController@getUnits')->where(['event_id', '[0-9]+'])->name('getUnits');
        Route::post('{event_id}/updateStatus', 'TheatersOfOperations\EventsController@updateStatus')->where(['event_id', '[0-9]+'])->name('updateStatus');
        Route::post('{event_id}/deployUnit', 'TheatersOfOperations\EventsController@deployUnit')->where(['event_id', '[0-9]+'])->name('deployUnit');
        Route::post('{event_id}/updateObservations', 'TheatersOfOperations\EventsController@updateObservations')->where(['event_id', '[0-9]+'])->name('updateObservations');
        Route::get('{event_id}/edit', 'TheatersOfOperations\TheaterOfOperationsController@editEvent')->where(['event_id', '[0-9]+'])->name('edit');
        Route::post('{event_id}/edit/', 'TheatersOfOperations\EventsController@edit')->where(['event_id', '[0-9]+']);
        Route::prefix('{event_id}/victims')->where(['event_id', '[0-9]+'])->name('victims.')->group(function () {
            Route::get('create', 'TheatersOfOperations\Events\VictimsController@create')->name('create');
            Route::get('{victim_id}/', 'TheatersOfOperations\Events\VictimsController@get')->where(['victim_id', '[0-9]+'])->name('get');
            Route::post('{victim_id}/assignUnit', 'TheatersOfOperations\Events\VictimsController@assignUnit')->where(['victim_id', '[0-9]+'])->name('assignUnit');
            Route::post('{victim_id}/updateData', 'TheatersOfOperations\Events\VictimsController@updateData')->where(['victim_id', '[0-9]+'])->name('updateData');
            Route::post('{victim_id}/updateDestination', 'TheatersOfOperations\Events\VictimsController@updateDestination')->where(['victim_id', '[0-9]+'])->name('updateDestination');
            Route::post('{victim_id}/updateStatus', 'TheatersOfOperations\Events\VictimsController@updateStatus')->where(['victim_id', '[0-9]+'])->name('updateStatus');
            Route::post('{victim_id}/updateTimings', 'TheatersOfOperations\Events\VictimsController@updateTimings')->where(['victim_id', '[0-9]+'])->name('updateTimings');
            Route::post('{victim_id}/updateObservations', 'TheatersOfOperations\Events\VictimsController@updateObservations')->where(['victim_id', '[0-9]+'])->name('updateObservations');
            Route::get('{victim_id}/delete', 'TheatersOfOperations\Events\VictimsController@delete')->where(['victim_id', '[0-9]+'])->name('delete');
        });

        Route::prefix('{event_id}/units')->where(['event_id', '[0-9]+'])->name('units.')->group(function () {
            Route::get('{event_unit_id}/', 'TheatersOfOperations\Events\UnitsController@get')->where(['event_unit_id', '[0-9]+'])->name('get');
            Route::post('{event_unit_id}/updateTimings', 'TheatersOfOperations\Events\UnitsController@updateTimings')->where(['event_unit_id', '[0-9]+'])->name('updateTimings');
        });
    });

    Route::prefix('{id}/units')->where(['id', '[0-9]+'])->name('units.')->group(function () {
        Route::get('create', 'TheatersOfOperations\TheaterOfOperationsController@createUnit')->name('create');
        Route::post('create', 'TheatersOfOperations\UnitsController@create');
        Route::get('recreate', 'TheatersOfOperations\TheaterOfOperationsController@recreateUnit')->name('recreate');
        Route::post('recreate', 'TheatersOfOperations\UnitsController@recreate');


        Route::get('{unit_id}/', 'TheatersOfOperations\UnitsController@single')->where(['unit_id', '[0-9]+'])->name('single');
        Route::get('{unit_id}/getBriefTimeTape', 'TheatersOfOperations\UnitsController@getBriefTimeTape')->where(['unit_id', '[0-9]+'])->name('getBriefTimeTape');
        Route::get('{unit_id}/getCrews', 'TheatersOfOperations\UnitsController@getCrews')->where(['id', '[0-9]+'])->name('getCrews');
        Route::get('{unit_id}/getCommunicationChannels', 'TheatersOfOperations\UnitsController@getCommunicationChannels')->where(['id', '[0-9]+'])->name('getCommunicationChannels');
        Route::post('{unit_id}/createCommunicationChannel', 'TheatersOfOperations\UnitsController@createCommunicationChannel')->where(['id', '[0-9]+'])->name('createCommunicationChannel');
        Route::get('{unit_id}/getGeotracking', 'TheatersOfOperations\UnitsController@getGeotracking')->where(['id', '[0-9]+'])->name('getGeotracking');
        Route::post('{unit_id}/createGeotracking', 'TheatersOfOperations\UnitsController@createGeotracking')->where(['id', '[0-9]+'])->name('createGeotracking');

        Route::post('{unit_id}/assignToPOI', 'TheatersOfOperations\UnitsController@assignToPOI')->where(['unit_id', '[0-9]+'])->name('assignToPOI');
        Route::post('{unit_id}/updateStatus', 'TheatersOfOperations\UnitsController@updateStatus')->where(['unit_id', '[0-9]+'])->name('updateStatus');
        Route::post('{unit_id}/updateObservations', 'TheatersOfOperations\UnitsController@updateObservations')->where(['unit_id', '[0-9]+'])->name('updateObservations');
        Route::get('{unit_id}/edit', 'TheatersOfOperations\TheaterOfOperationsController@editUnit')->where(['unit_id', '[0-9]+'])->name('edit');
        Route::post('{unit_id}/edit', 'TheatersOfOperations\UnitsController@edit')->where(['unit_id', '[0-9]+']);
        Route::get('{unit_id}/demobilize', 'TheatersOfOperations\UnitsController@demobilize')->where(['unit_id', '[0-9]+'])->name('demobilize');

        Route::prefix('{unit_id}/communication_channels')->where(['event_id', '[0-9]+'])->name('communication_channels.')->group(function () {
            Route::get('{communication_channel_id}/', 'TheatersOfOperations\UnitsController@getCommunicationChannel')->where(['communication_channel_id', '[0-9]+'])->name('get');
            Route::post('{communication_channel_id}/update', 'TheatersOfOperations\UnitsController@updateCommunicationChannel')->where(['communication_channel_id', '[0-9]+'])->name('update');
            Route::get('{communication_channel_id}/remove', 'TheatersOfOperations\UnitsController@removeCommunicationChannel')->where(['communication_channel_id', '[0-9]+'])->name('remove');
        });

        Route::prefix('{unit_id}/geotracking')->where(['event_id', '[0-9]+'])->name('geotracking.')->group(function () {
            Route::get('{geotracking_id}/', 'TheatersOfOperations\UnitsController@getGeotrackingSingle')->where(['geotracking_id', '[0-9]+'])->name('get');
            Route::post('{geotracking_id}/update', 'TheatersOfOperations\UnitsController@updateGeotracking')->where(['geotracking_id', '[0-9]+'])->name('update');
            Route::get('{geotracking_id}/remove', 'TheatersOfOperations\UnitsController@removeGeotracking')->where(['geotracking_id', '[0-9]+'])->name('remove');
        });
    });

    Route::prefix('{id}/crews')->where(['id', '[0-9]+'])->name('crews.')->group(function () {
        Route::get('create', 'TheatersOfOperations\TheaterOfOperationsController@createCrew')->name('create');
        Route::post('create', 'TheatersOfOperations\CrewsController@create');
        Route::get('recreate', 'TheatersOfOperations\TheaterOfOperationsController@recreateCrew')->name('recreate');
        Route::post('recreate', 'TheatersOfOperations\CrewsController@recreate');

        Route::get('{crew_id}/', 'TheatersOfOperations\CrewsController@single')->where(['crew_id', '[0-9]+'])->name('single');
        Route::get('{crew_id}/getBriefTimeTape', 'TheatersOfOperations\CrewsController@getBriefTimeTape')->where(['crew_id', '[0-9]+'])->name('getBriefTimeTape');
        Route::post('{crew_id}/assignToPOI', 'TheatersOfOperations\CrewsController@assignToPOI')->where(['crew_id', '[0-9]+'])->name('assignToPOI');
        Route::post('{crew_id}/assignToUnit', 'TheatersOfOperations\CrewsController@assignToUnit')->where(['crew_id', '[0-9]+'])->name('assignToUnit');
        Route::post('{crew_id}/updateObservations', 'TheatersOfOperations\CrewsController@updateObservations')->where(['crew_id', '[0-9]+'])->name('updateObservations');
        Route::get('{crew_id}/edit', 'TheatersOfOperations\TheaterOfOperationsController@editCrew')->where(['crew_id', '[0-9]+'])->name('edit');
        Route::post('{crew_id}/edit', 'TheatersOfOperations\CrewsController@edit')->where(['crew_id', '[0-9]+']);
        Route::get('{crew_id}/demobilize', 'TheatersOfOperations\CrewsController@demobilize')->where(['crew_id', '[0-9]+'])->name('demobilize');
    });

    Route::prefix('{id}/communication_channels')->where(['id', '[0-9]+'])->name('communication_channels.')->group(function () {
        Route::get('create', 'TheatersOfOperations\TheaterOfOperationsController@createCommunicationChannel')->name('create');
        Route::post('create', 'TheatersOfOperations\CommunicationChannelsController@create');
        Route::get('edit/{communication_channel_id}', 'TheatersOfOperations\TheaterOfOperationsController@editCommunicationChannel')->where(['communication_channel_id', '[0-9]+'])->name('edit');
        Route::post('edit/{communication_channel_id}', 'TheatersOfOperations\CommunicationChannelsController@edit')->where(['communication_channel_id', '[0-9]+']);
        Route::get('remove/{communication_channel_id}', 'TheatersOfOperations\CommunicationChannelsController@remove')->where(['communication_channel_id', '[0-9]+'])->name('remove');
    });
});
