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
    'reset'    => false,
    'confirm'  => false,
    'verify'   => false,
]);

Broadcast::routes();

Route::domain('painel.salop')->group(function () {
    Route::get('/', function () {
        if (Auth::user()) {
            return redirect()->route('panel.fop2');
        }
        return view('login');
    })->name('homepage');

    Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout')->name('logout');

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

        Route::get('/door_opener', function () {
            return view('door_opener');
        })->name('door_opener');

        Route::get('/change_password', function () {
            return view('change_password');
        })->name('change_password');
        Route::post('/change_password', 'Auth\ChangePasswordController@changePassword')->name('change_password');

        Route::get('users', 'ManagementController@users')->name('users');
        Route::get('reports', 'ManagementController@reports')->name('reports');
        Route::get('extensions', 'AdministrationController@extensions')->name('extensions');
    });

    Route::prefix('covid19')->name('covid19.')->group(function () {
        Route::get('panel', 'COVID19Controller@panel')->name('panel');

        Route::get('openCases', 'COVID19CaseController@getOpenCases')->name('openCases');
        Route::get('case/{id}', 'COVID19CaseController@getCase')->name('case');
        Route::get('case_patients/{id}', 'COVID19CaseController@getPatients')->name('case_patients');
        Route::get('case_team_members/{id}', 'COVID19CaseController@getTeamMembers')->name('case_team_members');
        Route::get('case_operators/{id}', 'COVID19CaseController@getOperators')->name('case_operators');
        Route::get('case_observations/{id}', 'COVID19CaseController@getObservations')->name('case_observations');


        Route::get('ambulances', 'COVID19AmbulanceController@getAmbulances')->name('ambulances');
        Route::get('ambulance/{id}', 'COVID19AmbulanceController@getAmbulance')->name('ambulance');
        Route::get('ambulance_contacts/{id}', 'COVID19AmbulanceController@getContacts')->name('ambulance_contacts');
        Route::get('ambulance_team_members/{id}', 'COVID19AmbulanceController@getTeamMembers')->name('ambulance_team_members');
        Route::post('ambulance_team_member', 'COVID19AmbulanceController@getTeamMember')->name('ambulance_team_member');





        Route::post('newCase', 'COVID19CaseController@newCase')->name('newCase');
        Route::post('newAmbulance', 'COVID19AmbulanceController@newAmbulance')->name('newAmbulance');
        Route::post('insertEvent', 'COVID19CaseController@insertEvent')->name('insertEvent');
        Route::post('insertAmbulance', 'COVID19CaseController@insertAmbulance')->name('insertAmbulance');
        Route::post('insertSIEMAmbulance', 'COVID19CaseController@insertSIEMAmbulance')->name('insertSIEMAmbulance');
        Route::post('updateCODUNumber', 'COVID19CaseController@updateCODUNumber')->name('updateCODUNumber');
        Route::post('updateCODULocalization', 'COVID19CaseController@updateCODULocalization')->name('updateCODULocalization');
        Route::post('updateActivationMean', 'COVID19CaseController@updateActivationMean')->name('updateActivationMean');
        Route::post('updateStreet', 'COVID19CaseController@updateStreet')->name('updateStreet');
        Route::post('updateRef', 'COVID19CaseController@updateRef')->name('updateRef');
        Route::post('updateParish', 'COVID19CaseController@updateParish')->name('updateParish');
        Route::post('updateCounty', 'COVID19CaseController@updateCounty')->name('updateCounty');
        Route::post('updateDistrict', 'COVID19CaseController@updateDistrict')->name('updateDistrict');
        Route::post('updateSource', 'COVID19CaseController@updateSource')->name('updateSource');
        Route::post('updateDestination', 'COVID19CaseController@updateDestination')->name('updateDestination');
        Route::post('updateDoctorResponsibleOnScene', 'COVID19CaseController@updateDoctorResponsibleOnScene')->name('updateDoctorResponsibleOnScene');
        Route::post('updateDoctorResponsibleOnDestination', 'COVID19CaseController@updateDoctorResponsibleOnDestination')->name('updateDoctorResponsibleOnDestination');
        Route::post('updateOnSceneUnits', 'COVID19CaseController@updateOnSceneUnits')->name('updateOnSceneUnits');
        Route::post('updateTotalDistance', 'COVID19CaseController@updateTotalDistance')->name('updateTotalDistance');
        Route::post('updateSALOPActivationStatus', 'COVID19CaseController@updateSALOPActivationStatus')->name('updateSALOPActivationStatus');
        Route::post('updateAMBActivationStatus', 'COVID19CaseController@updateAMBActivationStatus')->name('updateAMBActivationStatus');
        Route::post('updateBaseExitStatus', 'COVID19CaseController@updateBaseExitStatus')->name('updateBaseExitStatus');
        Route::post('updateArrivalOnSceneStatus', 'COVID19CaseController@updateArrivalOnSceneStatus')->name('updateArrivalOnSceneStatus');
        Route::post('updateDepartureFromSceneStatus', 'COVID19CaseController@updateDepartureFromSceneStatus')->name('updateDepartureFromSceneStatus');
        Route::post('updateArrivalOnDestinationStatus', 'COVID19CaseController@updateArrivalOnDestinationStatus')->name('updateArrivalOnDestinationStatus');
        Route::post('updateDepartureFromDestinationStatus', 'COVID19CaseController@updateDepartureFromDestinationStatus')->name('updateDepartureFromDestinationStatus');
        Route::post('updateBaseReturnStatus', 'COVID19CaseController@updateBaseReturnStatus')->name('updateBaseReturnStatus');
        Route::post('updateAvailableStatus', 'COVID19CaseController@updateAvailableStatus')->name('updateAvailableStatus');
        Route::post('updateCaseNotes', 'COVID19CaseController@updateNotes')->name('updateCaseNotes');

        Route::post('insertPatient', 'COVID19CaseController@insertPatient')->name('insertPatient');
        Route::post('updatePatientRNU', 'COVID19CaseController@updatePatientRNU')->name('updatePatientRNU');
        Route::post('updatePatientFirstname', 'COVID19CaseController@updatePatientFirstname')->name('updatePatientFirstname');
        Route::post('updatePatientLastname', 'COVID19CaseController@updatePatientLastname')->name('updatePatientLastname');
        Route::post('updatePatientSex', 'COVID19CaseController@updatePatientSex')->name('updatePatientSex');
        Route::post('updatePatientDoB', 'COVID19CaseController@updatePatientDoB')->name('updatePatientDoB');
        Route::post('updatePatientSuspect', 'COVID19CaseController@updatePatientSuspect')->name('updatePatientSuspect');
        Route::post('updatePatientSuspectValidation', 'COVID19CaseController@updatePatientSuspectValidation')->name('updatePatientSuspectValidation');
        Route::post('updatePatientConfirmed', 'COVID19CaseController@updatePatientConfirmed')->name('updatePatientConfirmed');
        Route::post('updatePatientInvasiveCare', 'COVID19CaseController@updatePatientInvasiveCare')->name('updatePatientInvasiveCare');
        Route::post('removePatient', 'COVID19CaseController@removePatient')->name('removePatient');

        Route::post('addObservation', 'COVID19CaseController@addObservation')->name('addObservation');
        Route::post('removeObservation', 'COVID19CaseController@removeObservation')->name('removeObservation');

        Route::post('insertTeamMember', 'COVID19CaseController@insertTeamMember')->name('insertTeamMember');
        Route::post('updateTeamMemberName', 'COVID19CaseController@updateTeamMemberName')->name('updateTeamMemberName');
        Route::post('updateTeamMemberAge', 'COVID19CaseController@updateTeamMemberAge')->name('updateTeamMemberAge');
        Route::post('updateTeamMemberContact', 'COVID19CaseController@updateTeamMemberContact')->name('updateTeamMemberContact');
        Route::post('updateTeamMemberType', 'COVID19CaseController@updateTeamMemberType')->name('updateTeamMemberType');
        Route::post('removeTeamMember', 'COVID19CaseController@removeTeamMember')->name('removeTeamMember');

        Route::post('ambulanceINOP', 'COVID19AmbulanceController@INOP')->name('ambulanceINOP');
        Route::post('ambulanceAvailable', 'COVID19AmbulanceController@available')->name('ambulanceAvailable');
        Route::post('ambulanceOnBase', 'COVID19AmbulanceController@onBase')->name('ambulanceOnBase');
        Route::post('ambulanceBaseExit', 'COVID19AmbulanceController@baseExit')->name('ambulanceBaseExit');
        Route::post('ambulanceArrivalOnScene', 'COVID19AmbulanceController@arrivalOnScene')->name('ambulanceArrivalOnScene');
        Route::post('ambulanceDepartureFromScene', 'COVID19AmbulanceController@departureFromScene')->name('ambulanceDepartureFromScene');
        Route::post('ambulanceArrivalOnDestination', 'COVID19AmbulanceController@arrivalOnDestination')->name('ambulanceArrivalOnDestination');
        Route::post('ambulanceDepartureFromDestination', 'COVID19AmbulanceController@departureFromDestination')->name('ambulanceDepartureFromDestination');
        Route::post('ambulanceBaseReturn', 'COVID19AmbulanceController@baseReturn')->name('ambulanceBaseReturn');

        Route::post('updateAmbulanceStructure', 'COVID19AmbulanceController@updateStructure')->name('updateAmbulanceStructure');
        Route::post('updateAmbulanceRegion', 'COVID19AmbulanceController@updateRegion')->name('updateAmbulanceRegion');
        Route::post('updateAmbulanceVehicleIdentification', 'COVID19AmbulanceController@updateVehicleIdentification')->name('updateAmbulanceVehicleIdentification');
        Route::post('updateAmbulanceActivePrevention', 'COVID19AmbulanceController@updateActivePrevention')->name('updateAmbulanceActivePrevention');
        Route::post('addContact', 'COVID19AmbulanceController@addContact')->name('addContact');
        Route::post('removeContact', 'COVID19AmbulanceController@removeContact')->name('removeContact');

        Route::post('cancelCase', 'COVID19CaseController@cancel')->name('cancelCase');
    });


    Route::prefix('data')->name('data.')->middleware('auth')->group(function () {
        Route::get('missed_calls.json', 'CDRMissedCallsController@fetch')->name('missed_calls');
        Route::get('callbacks.json', 'CDRBusyCallsController@fetch')->name('callbacks');
    });

    Route::prefix('actions')->name('actions.')->middleware('auth')->group(function () {
        Route::get('open_door', 'GDSAPI@openDoor')->name('open_door');
    });
});

Route::domain('goi.emergenciacvp.pt')->name('theaters_of_operations.')->middleware('auth')->group(function () {
    Route::get('/', 'TheatersOfOperationsPanelController@index')->name('index');
    Route::get('map', 'TheatersOfOperationsPanelController@map')->name('map');
    Route::get('timetape', 'TheatersOfOperationsPanelController@timetape')->name('timetape');


    Route::get('list', 'TheatersOfOperationsPanelController@list')->name('list');
    Route::get('getActive', 'TheatersOfOperations\TheaterOfOperationsController@getActive')->name('getActive');
    Route::get('getConcluded', 'TheatersOfOperations\TheaterOfOperationsController@getConcluded')->name('getConcluded');

    Route::get('create', 'TheatersOfOperationsPanelController@create')->name('create');
    Route::post('create', 'TheatersOfOperations\TheaterOfOperationsController@create');

    Route::get('info', 'TheatersOfOperations\TheaterOfOperationsController@info')->name('info');
    Route::get('units_info', 'TheatersOfOperations\TheaterOfOperationsController@units_info')->name('units_info');
    Route::get('events_info', 'TheatersOfOperations\TheaterOfOperationsController@events_info')->name('events_info');

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
    Route::get('{id}/getUnits', 'TheatersOfOperations\TheaterOfOperationsController@getUnits')->where(['id', '[0-9]+'])->name('getUnits');
    Route::get('{id}/getCrews', 'TheatersOfOperations\TheaterOfOperationsController@getCrews')->where(['id', '[0-9]+'])->name('getCrews');
    Route::get('{id}/getCommunicationChannels', 'TheatersOfOperations\TheaterOfOperationsController@getCommunicationChannels')->where(['id', '[0-9]+'])->name('getCommunicationChannels');
    Route::post('{id}/updateObservations', 'TheatersOfOperations\TheaterOfOperationsController@updateObservations')->where(['id', '[0-9]+'])->name('updateObservations');

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

        Route::get('{unit_id}/', 'TheatersOfOperations\UnitsController@single')->where(['unit_id', '[0-9]+'])->name('single');
        Route::get('{unit_id}/getBriefTimeTape', 'TheatersOfOperations\UnitsController@getBriefTimeTape')->where(['unit_id', '[0-9]+'])->name('getBriefTimeTape');
        Route::get('{unit_id}/getCrews', 'TheatersOfOperations\UnitsController@getCrews')->where(['id', '[0-9]+'])->name('getCrews');
        Route::get('{unit_id}/getCommunicationChannels', 'TheatersOfOperations\UnitsController@getCommunicationChannels')->where(['id', '[0-9]+'])->name('getCommunicationChannels');
        Route::post('{unit_id}/createCommunicationChannel', 'TheatersOfOperations\UnitsController@createCommunicationChannel')->where(['id', '[0-9]+'])->name('createCommunicationChannel');
        Route::post('{unit_id}/assignToPOI', 'TheatersOfOperations\UnitsController@assignToPOI')->where(['unit_id', '[0-9]+'])->name('assignToPOI');
        Route::post('{unit_id}/updateStatus', 'TheatersOfOperations\UnitsController@updateStatus')->where(['unit_id', '[0-9]+'])->name('updateStatus');
        Route::post('{unit_id}/updateObservations', 'TheatersOfOperations\UnitsController@updateObservations')->where(['unit_id', '[0-9]+'])->name('updateObservations');
        Route::get('{unit_id}/edit', 'TheatersOfOperations\TheaterOfOperationsController@editUnit')->where(['unit_id', '[0-9]+'])->name('edit');
        Route::post('{unit_id}/edit', 'TheatersOfOperations\UnitsController@edit')->where(['unit_id', '[0-9]+']);
        Route::get('{unit_id}/demobilize', 'TheatersOfOperations\UnitsController@demobilize')->where(['unit_id', '[0-9]+'])->name('demobilize');

        Route::prefix('{unit_id}/units')->where(['event_id', '[0-9]+'])->name('communication_channels.')->group(function () {
            Route::get('{communication_channel_id}/', 'TheatersOfOperations\UnitsController@getCommunicationChannel')->where(['communication_channel_id', '[0-9]+'])->name('get');
            Route::post('{communication_channel_id}/update', 'TheatersOfOperations\UnitsController@updateCommunicationChannel')->where(['communication_channel_id', '[0-9]+'])->name('update');
            Route::get('{communication_channel_id}/remove', 'TheatersOfOperations\UnitsController@removeCommunicationChannel')->where(['communication_channel_id', '[0-9]+'])->name('remove');
        });
    });

    Route::prefix('{id}/crews')->where(['id', '[0-9]+'])->name('crews.')->group(function () {
        Route::get('create', 'TheatersOfOperations\TheaterOfOperationsController@createCrew')->name('create');
        Route::post('create', 'TheatersOfOperations\CrewsController@create');

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
