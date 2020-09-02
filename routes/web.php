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
    Route::get('/', 'SALOP\SALOPController@index')->name('index');
    Route::middleware(['can:accessSALOP'])->group(function () {
        Route::get('phones', 'SALOP\SALOPController@fop2')->name('fop2');
        Route::get('missed_calls', 'SALOP\SALOPController@missed_calls')->name('missed_calls');
        Route::get('callbacks', 'SALOP\SALOPController@callbacks')->name('callbacks');
        Route::get('door_opener', 'SALOP\SALOPController@door_opener')->name('door_opener');
    });
    Route::middleware(['can:isManager'])->group(function () {
        Route::get('users', 'SALOP\ManagementController@users')->name('users');
        Route::get('reports', 'SALOP\ManagementController@reports')->name('reports');
    });
    Route::middleware(['can:isAdmin'])->group(function () {
        Route::get('extensions', 'SALOP\AdministrationController@extensions')->name('extensions');
    });

    Route::prefix('data')->name('data.')->middleware(['can:accessSALOP'])->group(function () {
        Route::get('missed_calls.json', 'SALOP\CDRMissedCallsController@fetch')->name('missed_calls');
        Route::get('callbacks.json', 'SALOP\CDRBusyCallsController@fetch')->name('callbacks');
    });

    Route::prefix('actions')->name('actions.')->middleware(['can:accessSALOP'])->group(function () {
        Route::get('open_door', 'SALOP\GDSAPI@openDoor')->name('open_door');
    });
});

Route::domain('goi.'.env('APP_DOMAIN'))->middleware(['auth','can:accessGOI'])->name('goi.')->group(function () {
    Route::get('/', 'GOI\GOIController@index')->name('index');
    Route::get('map', 'GOI\GOIController@map')->name('map');
    Route::prefix('timetape')->name('timetape.')->group(function () {
        Route::get('/', 'GOI\GOIController@timetape')->name('index');
        Route::get('all', 'GOI\TimeTapeController@all')->name('all');
        Route::get('to/{id}', 'GOI\TimeTapeController@to')->where(['id', '[0-9]+'])->name('to');
        Route::get('poi/{id}', 'GOI\TimeTapeController@poi')->where(['id', '[0-9]+'])->name('poi');
        Route::get('event/{id}', 'GOI\TimeTapeController@event')->where(['id', '[0-9]+'])->name('event');
        Route::get('unit/{id}', 'GOI\TimeTapeController@unit')->where(['id', '[0-9]+'])->name('unit');
        Route::get('crew/{id}', 'GOI\TimeTapeController@crew')->where(['id', '[0-9]+'])->name('crew');
        Route::prefix('objects')->name('objects.')->group(function () {
            Route::get('to', 'GOI\TimeTapeController@to_objects')->name('to');
            Route::get('poi', 'GOI\TimeTapeController@poi_objects')->name('poi');
            Route::get('event', 'GOI\TimeTapeController@event_objects')->name('event');
            Route::get('unit', 'GOI\TimeTapeController@unit_objects')->name('unit');
            Route::get('crew', 'GOI\TimeTapeController@crew_objects')->name('crew');
        });
    });



    Route::get('list', 'GOI\GOIController@list')->name('list');
    Route::get('getActive', 'GOI\TheaterOfOperationsController@getActive')->name('getActive');
    Route::get('getConcluded', 'GOI\TheaterOfOperationsController@getConcluded')->name('getConcluded');

    Route::get('create', 'GOI\GOIController@create')->name('create');
    Route::post('create', 'GOI\TheaterOfOperationsController@create');

    Route::get('info', 'GOI\TheaterOfOperationsController@info')->name('info');
    Route::get('units_info', 'GOI\TheaterOfOperationsController@units_info')->name('units_info');
    Route::get('events_info', 'GOI\TheaterOfOperationsController@events_info')->name('events_info');
    Route::get('pois_info', 'GOI\TheaterOfOperationsController@pois_info')->name('pois_info');
    Route::get('unit/{unit_id}', 'GOI\TheaterOfOperationsController@unit_redirect')->where(['unit_id', '[0-9]+']);
    Route::get('event/{event_id}', 'GOI\TheaterOfOperationsController@event_redirect')->where(['event_id', '[0-9]+']);
    Route::get('poi/{poi_id}', 'GOI\TheaterOfOperationsController@poi_redirect')->where(['poi_id', '[0-9]+']);



    Route::get('{id}/', 'GOI\TheaterOfOperationsController@single')->where(['id', '[0-9]+'])->name('single');
    Route::get('{id}/edit', 'GOI\TheaterOfOperationsController@edit')->where(['id', '[0-9]+'])->name('edit');
    Route::post('{id}/edit', 'GOI\TheaterOfOperationsController@postEdit')->where(['id', '[0-9]+']);
    Route::post('{id}/addToTimeTape', 'GOI\TheaterOfOperationsController@addToTimeTape')->where(['id', '[0-9]+'])->name('addToTimeTape');
    Route::get('{id}/close', 'GOI\TheaterOfOperationsController@close')->where(['id', '[0-9]+'])->name('close');
    Route::get('{id}/reopen', 'GOI\TheaterOfOperationsController@reopen')->where(['id', '[0-9]+'])->name('reopen');
    Route::get('{id}/getBriefTimeTape', 'GOI\TheaterOfOperationsController@getBriefTimeTape')->where(['id', '[0-9]+'])->name('getBriefTimeTape');
    Route::get('{id}/getCoordination', 'GOI\TheaterOfOperationsController@getCoordination')->where(['id', '[0-9]+'])->name('getCoordination');
    Route::get('{id}/getPOIs', 'GOI\TheaterOfOperationsController@getPOIs')->where(['id', '[0-9]+'])->name('getPOIs');
    Route::get('{id}/getEvents', 'GOI\TheaterOfOperationsController@getEvents')->where(['id', '[0-9]+'])->name('getEvents');
    Route::get('{id}/getActiveEvents', 'GOI\TheaterOfOperationsController@getActiveEvents')->where(['id', '[0-9]+'])->name('getActiveEvents');
    Route::get('{id}/getUnits', 'GOI\TheaterOfOperationsController@getUnits')->where(['id', '[0-9]+'])->name('getUnits');
    Route::get('{id}/getActiveUnits', 'GOI\TheaterOfOperationsController@getActiveUnits')->where(['id', '[0-9]+'])->name('getActiveUnits');
    Route::get('{id}/getCrews', 'GOI\TheaterOfOperationsController@getCrews')->where(['id', '[0-9]+'])->name('getCrews');
    Route::get('{id}/getActiveCrews', 'GOI\TheaterOfOperationsController@getActiveCrews')->where(['id', '[0-9]+'])->name('getActiveCrews');
    Route::get('{id}/getCommunicationChannels', 'GOI\TheaterOfOperationsController@getCommunicationChannels')->where(['id', '[0-9]+'])->name('getCommunicationChannels');
    Route::post('{id}/updateObservations', 'GOI\TheaterOfOperationsController@updateObservations')->where(['id', '[0-9]+'])->name('updateObservations');

    Route::prefix('{id}/objects')->where(['id', '[0-9]+'])->name('objects.')->group(function () {
        Route::get('poi', 'GOI\TheaterOfOperationsController@poi_objects')->name('poi');
        Route::get('event', 'GOI\TheaterOfOperationsController@event_objects')->name('event');
        Route::get('unit', 'GOI\TheaterOfOperationsController@unit_objects')->name('unit');
        Route::get('crew', 'GOI\TheaterOfOperationsController@crew_objects')->name('crew');
    });

    /*Route::prefix('crews')->name('crews.')->group(function () {
        Route::get('/', 'GOI\CrewsController@index')->name('index');
    });

    Route::prefix('units')->name('units.')->group(function () {
        Route::get('/', 'GOI\CrewsController@index')->name('index');
    });*/

    Route::prefix('{id}/coordination')->where(['id', '[0-9]+'])->name('coordination.')->group(function () {
        Route::get('create', 'GOI\TheaterOfOperationsController@createCoordination')->name('create');
        Route::post('create', 'GOI\CoordinationController@create');
        Route::get('edit/{coordination_id}', 'GOI\TheaterOfOperationsController@editCoordination')->where(['coordination_id', '[0-9]+'])->name('edit');
        Route::post('edit/{coordination_id}', 'GOI\CoordinationController@edit')->where(['coordination_id', '[0-9]+']);
        Route::get('remove/{coordination_id}', 'GOI\CoordinationController@remove')->where(['coordination_id', '[0-9]+'])->name('remove');
    });

    Route::prefix('{id}/pois')->where(['id', '[0-9]+'])->name('pois.')->group(function () {
        Route::get('create', 'GOI\TheaterOfOperationsController@createPOI')->name('create');
        Route::post('create', 'GOI\POIsController@create');
        Route::get('{poi_id}/edit/', 'GOI\TheaterOfOperationsController@editPOI')->where(['poi_id', '[0-9]+'])->name('edit');
        Route::post('{poi_id}/edit/', 'GOI\POIsController@edit')->where(['poi_id', '[0-9]+']);
        Route::get('{poi_id}/remove/', 'GOI\POIsController@remove')->where(['poi_id', '[0-9]+'])->name('remove');
    });

    Route::prefix('{id}/events')->where(['id', '[0-9]+'])->name('events.')->group(function () {
        Route::get('create', 'GOI\TheaterOfOperationsController@createEvent')->name('create');
        Route::post('create', 'GOI\EventsController@create');

        Route::get('{event_id}/', 'GOI\EventsController@single')->where(['event_id', '[0-9]+'])->name('single');
        Route::get('{event_id}/getBriefTimeTape', 'GOI\EventsController@getBriefTimeTape')->where(['event_id', '[0-9]+'])->name('getBriefTimeTape');
        Route::get('{event_id}/getVictims', 'GOI\EventsController@getVictims')->where(['event_id', '[0-9]+'])->name('getVictims');
        Route::get('{event_id}/getUnits', 'GOI\EventsController@getUnits')->where(['event_id', '[0-9]+'])->name('getUnits');
        Route::post('{event_id}/updateStatus', 'GOI\EventsController@updateStatus')->where(['event_id', '[0-9]+'])->name('updateStatus');
        Route::post('{event_id}/deployUnit', 'GOI\EventsController@deployUnit')->where(['event_id', '[0-9]+'])->name('deployUnit');
        Route::post('{event_id}/updateObservations', 'GOI\EventsController@updateObservations')->where(['event_id', '[0-9]+'])->name('updateObservations');
        Route::get('{event_id}/edit', 'GOI\TheaterOfOperationsController@editEvent')->where(['event_id', '[0-9]+'])->name('edit');
        Route::post('{event_id}/edit/', 'GOI\EventsController@edit')->where(['event_id', '[0-9]+']);
        Route::prefix('{event_id}/victims')->where(['event_id', '[0-9]+'])->name('victims.')->group(function () {
            Route::get('create', 'GOI\Events\VictimsController@create')->name('create');
            Route::get('{victim_id}/', 'GOI\Events\VictimsController@get')->where(['victim_id', '[0-9]+'])->name('get');
            Route::post('{victim_id}/assignUnit', 'GOI\Events\VictimsController@assignUnit')->where(['victim_id', '[0-9]+'])->name('assignUnit');
            Route::post('{victim_id}/updateData', 'GOI\Events\VictimsController@updateData')->where(['victim_id', '[0-9]+'])->name('updateData');
            Route::post('{victim_id}/updateDestination', 'GOI\Events\VictimsController@updateDestination')->where(['victim_id', '[0-9]+'])->name('updateDestination');
            Route::post('{victim_id}/updateStatus', 'GOI\Events\VictimsController@updateStatus')->where(['victim_id', '[0-9]+'])->name('updateStatus');
            Route::post('{victim_id}/updateTimings', 'GOI\Events\VictimsController@updateTimings')->where(['victim_id', '[0-9]+'])->name('updateTimings');
            Route::post('{victim_id}/updateObservations', 'GOI\Events\VictimsController@updateObservations')->where(['victim_id', '[0-9]+'])->name('updateObservations');
            Route::get('{victim_id}/delete', 'GOI\Events\VictimsController@delete')->where(['victim_id', '[0-9]+'])->name('delete');
        });

        Route::prefix('{event_id}/units')->where(['event_id', '[0-9]+'])->name('units.')->group(function () {
            Route::get('{event_unit_id}/', 'GOI\Events\UnitsController@get')->where(['event_unit_id', '[0-9]+'])->name('get');
            Route::post('{event_unit_id}/updateTimings', 'GOI\Events\UnitsController@updateTimings')->where(['event_unit_id', '[0-9]+'])->name('updateTimings');
        });
    });

    Route::prefix('{id}/units')->where(['id', '[0-9]+'])->name('units.')->group(function () {
        Route::get('create', 'GOI\TheaterOfOperationsController@createUnit')->name('create');
        Route::post('create', 'GOI\UnitsController@create');
        Route::get('recreate', 'GOI\TheaterOfOperationsController@recreateUnit')->name('recreate');
        Route::post('recreate', 'GOI\UnitsController@recreate');


        Route::get('{unit_id}/', 'GOI\UnitsController@single')->where(['unit_id', '[0-9]+'])->name('single');
        Route::get('{unit_id}/getBriefTimeTape', 'GOI\UnitsController@getBriefTimeTape')->where(['unit_id', '[0-9]+'])->name('getBriefTimeTape');
        Route::get('{unit_id}/getCrews', 'GOI\UnitsController@getCrews')->where(['id', '[0-9]+'])->name('getCrews');
        Route::get('{unit_id}/getCommunicationChannels', 'GOI\UnitsController@getCommunicationChannels')->where(['id', '[0-9]+'])->name('getCommunicationChannels');
        Route::post('{unit_id}/createCommunicationChannel', 'GOI\UnitsController@createCommunicationChannel')->where(['id', '[0-9]+'])->name('createCommunicationChannel');
        Route::get('{unit_id}/getGeotracking', 'GOI\UnitsController@getGeotracking')->where(['id', '[0-9]+'])->name('getGeotracking');
        Route::post('{unit_id}/createGeotracking', 'GOI\UnitsController@createGeotracking')->where(['id', '[0-9]+'])->name('createGeotracking');

        Route::post('{unit_id}/assignToPOI', 'GOI\UnitsController@assignToPOI')->where(['unit_id', '[0-9]+'])->name('assignToPOI');
        Route::post('{unit_id}/updateStatus', 'GOI\UnitsController@updateStatus')->where(['unit_id', '[0-9]+'])->name('updateStatus');
        Route::post('{unit_id}/updateObservations', 'GOI\UnitsController@updateObservations')->where(['unit_id', '[0-9]+'])->name('updateObservations');
        Route::get('{unit_id}/edit', 'GOI\TheaterOfOperationsController@editUnit')->where(['unit_id', '[0-9]+'])->name('edit');
        Route::post('{unit_id}/edit', 'GOI\UnitsController@edit')->where(['unit_id', '[0-9]+']);
        Route::get('{unit_id}/demobilize', 'GOI\UnitsController@demobilize')->where(['unit_id', '[0-9]+'])->name('demobilize');

        Route::prefix('{unit_id}/communication_channels')->where(['event_id', '[0-9]+'])->name('communication_channels.')->group(function () {
            Route::get('{communication_channel_id}/', 'GOI\UnitsController@getCommunicationChannel')->where(['communication_channel_id', '[0-9]+'])->name('get');
            Route::post('{communication_channel_id}/update', 'GOI\UnitsController@updateCommunicationChannel')->where(['communication_channel_id', '[0-9]+'])->name('update');
            Route::get('{communication_channel_id}/remove', 'GOI\UnitsController@removeCommunicationChannel')->where(['communication_channel_id', '[0-9]+'])->name('remove');
        });

        Route::prefix('{unit_id}/geotracking')->where(['event_id', '[0-9]+'])->name('geotracking.')->group(function () {
            Route::get('{geotracking_id}/', 'GOI\UnitsController@getGeotrackingSingle')->where(['geotracking_id', '[0-9]+'])->name('get');
            Route::post('{geotracking_id}/update', 'GOI\UnitsController@updateGeotracking')->where(['geotracking_id', '[0-9]+'])->name('update');
            Route::get('{geotracking_id}/remove', 'GOI\UnitsController@removeGeotracking')->where(['geotracking_id', '[0-9]+'])->name('remove');
        });
    });

    Route::prefix('{id}/crews')->where(['id', '[0-9]+'])->name('crews.')->group(function () {
        Route::get('create', 'GOI\TheaterOfOperationsController@createCrew')->name('create');
        Route::post('create', 'GOI\CrewsController@create');
        Route::get('recreate', 'GOI\TheaterOfOperationsController@recreateCrew')->name('recreate');
        Route::post('recreate', 'GOI\CrewsController@recreate');

        Route::get('{crew_id}/', 'GOI\CrewsController@single')->where(['crew_id', '[0-9]+'])->name('single');
        Route::get('{crew_id}/getBriefTimeTape', 'GOI\CrewsController@getBriefTimeTape')->where(['crew_id', '[0-9]+'])->name('getBriefTimeTape');
        Route::post('{crew_id}/assignToPOI', 'GOI\CrewsController@assignToPOI')->where(['crew_id', '[0-9]+'])->name('assignToPOI');
        Route::post('{crew_id}/assignToUnit', 'GOI\CrewsController@assignToUnit')->where(['crew_id', '[0-9]+'])->name('assignToUnit');
        Route::post('{crew_id}/updateObservations', 'GOI\CrewsController@updateObservations')->where(['crew_id', '[0-9]+'])->name('updateObservations');
        Route::get('{crew_id}/edit', 'GOI\TheaterOfOperationsController@editCrew')->where(['crew_id', '[0-9]+'])->name('edit');
        Route::post('{crew_id}/edit', 'GOI\CrewsController@edit')->where(['crew_id', '[0-9]+']);
        Route::get('{crew_id}/demobilize', 'GOI\CrewsController@demobilize')->where(['crew_id', '[0-9]+'])->name('demobilize');
    });

    Route::prefix('{id}/communication_channels')->where(['id', '[0-9]+'])->name('communication_channels.')->group(function () {
        Route::get('create', 'GOI\TheaterOfOperationsController@createCommunicationChannel')->name('create');
        Route::post('create', 'GOI\CommunicationChannelsController@create');
        Route::get('edit/{communication_channel_id}', 'GOI\TheaterOfOperationsController@editCommunicationChannel')->where(['communication_channel_id', '[0-9]+'])->name('edit');
        Route::post('edit/{communication_channel_id}', 'GOI\CommunicationChannelsController@edit')->where(['communication_channel_id', '[0-9]+']);
        Route::get('remove/{communication_channel_id}', 'GOI\CommunicationChannelsController@remove')->where(['communication_channel_id', '[0-9]+'])->name('remove');
    });
});
