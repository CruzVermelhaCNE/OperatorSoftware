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
