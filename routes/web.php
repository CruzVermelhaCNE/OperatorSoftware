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

Route::get('/', function () {
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
    Route::get('case_operators/{id}', 'COVID19CaseController@getOperators')->name('case_operators');
    Route::get('case_observations/{id}', 'COVID19CaseController@getObservations')->name('case_observations');

    Route::get('ambulances', 'COVID19AmbulanceController@getAmbulances')->name('ambulances');
    Route::get('ambulance/{id}', 'COVID19AmbulanceController@getAmbulance')->name('ambulance');
    Route::get('ambulance_contacts/{id}', 'COVID19AmbulanceController@getContacts')->name('ambulance_contacts');



    Route::post('newCase', 'COVID19CaseController@newCase')->name('newCase');
    Route::post('newAmbulance', 'COVID19AmbulanceController@newAmbulance')->name('newAmbulance');
    Route::post('insertPatient', 'COVID19CaseController@insertPatient')->name('insertPatient');
    Route::post('insertEvent', 'COVID19CaseController@insertEvent')->name('insertEvent');
    Route::post('insertTeam', 'COVID19CaseController@insertTeam')->name('insertTeam');
    Route::post('insertAmbulance', 'COVID19CaseController@insertAmbulance')->name('insertAmbulance');
    Route::post('insertSIEMAmbulance', 'COVID19CaseController@insertSIEMAmbulance')->name('insertSIEMAmbulance');
    Route::post('updateCODUNumber', 'COVID19CaseController@updateCODUNumber')->name('updateCODUNumber');
    Route::post('updateCODULocalization', 'COVID19CaseController@updateCODULocalization')->name('updateCODULocalization');
    Route::post('updateActivationMean', 'COVID19CaseController@updateActivationMean')->name('updateActivationMean');
    Route::post('updateRNU', 'COVID19CaseController@updateRNU')->name('updateRNU');
    Route::post('updateLastName', 'COVID19CaseController@updateLastName')->name('updateLastName');
    Route::post('updateFirstName', 'COVID19CaseController@updateFirstName')->name('updateFirstName');
    Route::post('updateSex', 'COVID19CaseController@updateSex')->name('updateSex');
    Route::post('updateDoB', 'COVID19CaseController@updateDoB')->name('updateDoB');
    Route::post('updateSuspect', 'COVID19CaseController@updateSuspect')->name('updateSuspect');
    Route::post('updateSuspectValidation', 'COVID19CaseController@updateSuspectValidation')->name('updateSuspectValidation');
    Route::post('updateConfirmed', 'COVID19CaseController@updateConfirmed')->name('updateConfirmed');
    Route::post('updateInvasiveCare', 'COVID19CaseController@updateInvasiveCare')->name('updateInvasiveCare');
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
    Route::post('updateDriverName', 'COVID19CaseController@updateDriverName')->name('updateDriverName');
    Route::post('updateDriverAge', 'COVID19CaseController@updateDriverAge')->name('updateDriverAge');
    Route::post('updateDriverContact', 'COVID19CaseController@updateDriverContact')->name('updateDriverContact');
    Route::post('updateRescuerName', 'COVID19CaseController@updateRescuerName')->name('updateRescuerName');
    Route::post('updateRescuerAge', 'COVID19CaseController@updateRescuerAge')->name('updateRescuerAge');
    Route::post('updateRescuerContact', 'COVID19CaseController@updateRescuerContact')->name('updateRescuerContact');
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
    Route::post('addObservation', 'COVID19CaseController@addObservation')->name('addObservation');
    Route::post('removeObservation', 'COVID19CaseController@removeObservation')->name('removeObservation');

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
