<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\COVID19Ambulance;
use App\COVID19Case;
use App\Http\Requests\COVID19CancelCase;
use App\Http\Requests\COVID19InsertAmbulance;
use App\Http\Requests\COVID19InsertEvent;
use App\Http\Requests\COVID19InsertPatient;
use App\Http\Requests\COVID19InsertSIEMAmbulance;
use App\Http\Requests\COVID19InsertTeam;
use App\Http\Requests\COVID19NewCase;
use App\Http\Requests\COVID19UpdateActivationMean;
use App\Http\Requests\COVID19UpdateAMBActivationStatus;
use App\Http\Requests\COVID19UpdateArrivalOnDestinationStatus;
use App\Http\Requests\COVID19UpdateArrivalOnSceneStatus;
use App\Http\Requests\COVID19UpdateAvailableStatus;
use App\Http\Requests\COVID19UpdateBaseExitStatus;
use App\Http\Requests\COVID19UpdateBaseReturnStatus;
use App\Http\Requests\COVID19UpdateCODULocalization;
use App\Http\Requests\COVID19UpdateCODUNumber;
use App\Http\Requests\COVID19UpdateConfirmed;
use App\Http\Requests\COVID19UpdateCounty;
use App\Http\Requests\COVID19UpdateDepartureFromDestinationStatus;
use App\Http\Requests\COVID19UpdateDepartureFromSceneStatus;
use App\Http\Requests\COVID19UpdateDestination;
use App\Http\Requests\COVID19UpdateDistrict;
use App\Http\Requests\COVID19UpdateDoB;
use App\Http\Requests\COVID19UpdateDoctorResponsibleOnDestination;
use App\Http\Requests\COVID19UpdateDoctorResponsibleOnScene;
use App\Http\Requests\COVID19UpdateDriverAge;
use App\Http\Requests\COVID19UpdateDriverContact;
use App\Http\Requests\COVID19UpdateDriverName;
use App\Http\Requests\COVID19UpdateFirstName;
use App\Http\Requests\COVID19UpdateInvasiveCare;
use App\Http\Requests\COVID19UpdateLastName;
use App\Http\Requests\COVID19UpdateOnSceneUnits;
use App\Http\Requests\COVID19UpdateParish;
use App\Http\Requests\COVID19UpdateRef;
use App\Http\Requests\COVID19UpdateRescuerAge;
use App\Http\Requests\COVID19UpdateRescuerContact;
use App\Http\Requests\COVID19UpdateRescuerName;
use App\Http\Requests\COVID19UpdateRNU;
use App\Http\Requests\COVID19UpdateSALOPActivationStatus;
use App\Http\Requests\COVID19UpdateSex;
use App\Http\Requests\COVID19UpdateSource;
use App\Http\Requests\COVID19UpdateStreet;
use App\Http\Requests\COVID19UpdateSuspect;
use App\Http\Requests\COVID19UpdateSuspectValidation;
use App\Http\Requests\COVID19UpdateTotalDistance;
use App\Notifications\COVID19SlackNotification;
use Carbon\Carbon;

class COVID19CaseController extends Controller
{
    public function newCase(COVID19NewCase $request)
    {
        $validated = $request->validated();
        if ($validated['CODU_number'] == -1) {
            $validated['CODU_number'] = null;
        }
        if ($validated['CODU_localization'] == -1) {
            $validated['CODU_localization'] = null;
        }
        COVID19Case::createCase($validated['CODU_number'], $validated['CODU_localization'], $validated['activation_mean']);
    }

    public function getOpenCases()
    {
        $cases = COVID19Case::where('status_AMB_activation', null)->get();
        return response()->json($cases);
    }

    public function getCase($id)
    {
        $case = COVID19Case::find($id);
        return response()->json($case);
    }

    public function getOperators($id)
    {
        $case = COVID19Case::find($id);
        $operators = $case->operators;
        $operator_names = [];
        foreach ($operators as $operator) {
            array_push($operator_names,$operator->user->name);
        }
        return response()->json($operator_names);
    }

    public function getObservations($id)
    {
        $case = COVID19Case::find($id);
        return response()->json($case->observations);
    }

    public function insertPatient(COVID19InsertPatient $request)
    {
        $validated = $request->validated();
        $case      = COVID19Case::find($validated['id']);
        $case->addPatientInformation($validated['rnu'], $validated['firstname'], $validated['lastname'], $validated['sex'], $validated['DoB'], $validated['suspect'], $validated['suspect_validation'], $validated['confirmed'], $validated['invasive_care']);
    }

    public function insertEvent(COVID19InsertEvent $request)
    {
        $validated = $request->validated();
        $case      = COVID19Case::find($validated['id']);
        $case->addEventInformation($validated['street'], $validated['parish'], $validated['county'], $validated['district'], $validated['ref'], $validated['source'], null, null, $validated['destination'], null, null, $validated['doctor_responsible_on_scene'], $validated['doctor_responsible_on_destination'], $validated['on_scene_units'], $validated['total_distance']);
    }

    public function insertTeam(COVID19InsertTeam $request)
    {
        $validated = $request->validated();
        $case      = COVID19Case::find($validated['id']);
        $case->addTeamInformation($validated['driver_name'], $validated['driver_age'], $validated['driver_contact'], $validated['rescuer_name'], $validated['rescuer_age'], $validated['rescuer_contact']);
    }

    public function insertAmbulance(COVID19InsertAmbulance $request)
    {
        $validated     = $request->validated();
        $case          = COVID19Case::find($validated['id']);
        $ambulance     = COVID19Ambulance::find($validated['ambulance_id']);
        $old_ambulance = COVID19Ambulance::where('case_id', '=', $case->id)->get();
        if ($old_ambulance->count() == 1) {
            $old_ambulance->first()->statusINOP(null);
            $case->notify(new COVID19SlackNotification('*ATIVAÇÃO COVID-19 | '.$old_ambulance->structure.' ANULADA*'));
        }
        $ambulance->activate($case->id, null, null, null, null, null, null, null);
        $case->notify(new COVID19SlackNotification('*ATIVAÇÃO COVID-19 | '.$ambulance->structure."*\nOrigem: ".$case->complete_source()."\n".$case->source."\nDestino: ".$case->destination."\nCODU: ".$case->CODU_number));
    }

    public function insertSIEMAmbulance(COVID19InsertSIEMAmbulance $request)
    {
        $validated     = $request->validated();
        $case          = COVID19Case::find($validated['id']);
        $old_ambulance = COVID19Ambulance::where('case_id', '=', $case->id)->get();
        if ($old_ambulance->count() == 1) {
            $old_ambulance->first()->INOP(null);
            $case->notify(new COVID19SlackNotification('*ATIVAÇÃO COVID-19 | '.$old_ambulance->structure.' ANULADA*'));
        }
        $case->statusActivation(Carbon::now());
        $case->addVehicleInformation($validated['structure'], $validated['vehicle_identification'], $validated['vehicle_type']);
    }

    public function updateCODUNumber(COVID19UpdateCODUNumber $request)
    {
        $validated = $request->validated();
        if ($validated['CODU_number'] == -1) {
            $validated['CODU_number'] = null;
        }
        $case = COVID19Case::find($validated['id']);
        $case->updateCODUNumber($validated['CODU_number']);
    }

    public function updateCODULocalization(COVID19UpdateCODULocalization $request)
    {
        $validated = $request->validated();
        if ($validated['CODU_localization'] == -1) {
            $validated['CODU_localization'] = null;
        }
        $case = COVID19Case::find($validated['id']);
        $case->updateCODULocalization($validated['CODU_localization']);
    }

    public function updateActivationMean(COVID19UpdateActivationMean $request)
    {
        $validated = $request->validated();
        $case      = COVID19Case::find($validated['id']);
        $case->updateActivationMean($validated['activation_mean']);
    }

    public function updateRNU(COVID19UpdateRNU $request)
    {
        $validated = $request->validated();
        $case      = COVID19Case::find($validated['id']);
        $case->updateRNU($validated['rnu']);
    }

    public function updateLastName(COVID19UpdateLastName $request)
    {
        $validated = $request->validated();
        $case      = COVID19Case::find($validated['id']);
        $case->updateLastName($validated['lastname']);
    }

    public function updateFirstName(COVID19UpdateFirstName $request)
    {
        $validated = $request->validated();
        $case      = COVID19Case::find($validated['id']);
        $case->updateFirstName($validated['firstname']);
    }

    public function updateSex(COVID19UpdateSex $request)
    {
        $validated = $request->validated();
        $case      = COVID19Case::find($validated['id']);
        $case->updateSex($validated['sex']);
    }

    public function updateDoB(COVID19UpdateDoB $request)
    {
        $validated = $request->validated();
        $case      = COVID19Case::find($validated['id']);
        $case->updateDoB($validated['dob']);
    }

    public function updateSuspect(COVID19UpdateSuspect $request)
    {
        $validated = $request->validated();
        $case      = COVID19Case::find($validated['id']);
        $case->updateSuspect($validated['suspect']);
    }

    public function updateSuspectValidation(COVID19UpdateSuspectValidation $request)
    {
        $validated = $request->validated();
        $case      = COVID19Case::find($validated['id']);
        $case->updateSuspectValidation($validated['suspect_validation']);
    }

    public function updateConfirmed(COVID19UpdateConfirmed $request)
    {
        $validated = $request->validated();
        $case      = COVID19Case::find($validated['id']);
        $case->updateConfirmed($validated['confirmed']);
    }

    public function updateInvasiveCare(COVID19UpdateInvasiveCare $request)
    {
        $validated = $request->validated();
        $case      = COVID19Case::find($validated['id']);
        $case->updateInvasiveCare($validated['invasive_care']);
    }

    public function updateStreet(COVID19UpdateStreet $request)
    {
        $validated = $request->validated();
        $case      = COVID19Case::find($validated['id']);
        $case->updateStreet($validated['street']);
    }

    public function updateRef(COVID19UpdateRef $request)
    {
        $validated = $request->validated();
        $case      = COVID19Case::find($validated['id']);
        $case->updateRef($validated['ref']);
    }

    public function updateParish(COVID19UpdateParish $request)
    {
        $validated = $request->validated();
        $case      = COVID19Case::find($validated['id']);
        $case->updateParish($validated['parish']);
    }

    public function updateCounty(COVID19UpdateCounty $request)
    {
        $validated = $request->validated();
        $case      = COVID19Case::find($validated['id']);
        $case->updateCounty($validated['county']);
    }

    public function updateDistrict(COVID19UpdateDistrict $request)
    {
        $validated = $request->validated();
        $case      = COVID19Case::find($validated['id']);
        $case->updateDistrict($validated['district']);
    }

    public function updateSource(COVID19UpdateSource $request)
    {
        $validated = $request->validated();
        $case      = COVID19Case::find($validated['id']);
        $case->updateSource($validated['source']);
    }

    public function updateDestination(COVID19UpdateDestination $request)
    {
        $validated = $request->validated();
        $case      = COVID19Case::find($validated['id']);
        $case->updateDestination($validated['destination']);
    }

    public function updateDoctorResponsibleOnScene(COVID19UpdateDoctorResponsibleOnScene $request)
    {
        $validated = $request->validated();
        $case      = COVID19Case::find($validated['id']);
        $case->updateDoctorResponsibleOnScene($validated['doctor_responsible_on_scene']);
    }

    public function updateDoctorResponsibleOnDestination(COVID19UpdateDoctorResponsibleOnDestination $request)
    {
        $validated = $request->validated();
        $case      = COVID19Case::find($validated['id']);
        $case->updateDoctorResponsibleOnDestination($validated['doctor_responsible_on_destination']);
    }

    public function updateOnSceneUnits(COVID19UpdateOnSceneUnits $request)
    {
        $validated = $request->validated();
        $case      = COVID19Case::find($validated['id']);
        $case->updateOnSceneUnits($validated['on_scene_units']);
    }

    public function updateTotalDistance(COVID19UpdateTotalDistance $request)
    {
        $validated = $request->validated();
        $case      = COVID19Case::find($validated['id']);
        $case->updateTotalDistance($validated['total_distance']);
    }

    public function updateDriverName(COVID19UpdateDriverName $request)
    {
        $validated = $request->validated();
        $case      = COVID19Case::find($validated['id']);
        $case->updateDriverName($validated['driver_name']);
    }

    public function updateDriverAge(COVID19UpdateDriverAge $request)
    {
        $validated = $request->validated();
        $case      = COVID19Case::find($validated['id']);
        $case->updateDriverAge($validated['driver_age']);
    }

    public function updateDriverContact(COVID19UpdateDriverContact $request)
    {
        $validated = $request->validated();
        $case      = COVID19Case::find($validated['id']);
        $case->updateDriverContact($validated['driver_contact']);
    }

    public function updateRescuerName(COVID19UpdateRescuerName $request)
    {
        $validated = $request->validated();
        $case      = COVID19Case::find($validated['id']);
        $case->updateRescuerName($validated['rescuer_name']);
    }

    public function updateRescuerAge(COVID19UpdateRescuerAge $request)
    {
        $validated = $request->validated();
        $case      = COVID19Case::find($validated['id']);
        $case->updateRescuerAge($validated['rescuer_age']);
    }

    public function updateRescuerContact(COVID19UpdateRescuerContact $request)
    {
        $validated = $request->validated();
        $case      = COVID19Case::find($validated['id']);
        $case->updateRescuerContact($validated['rescuer_contact']);
    }

    public function updateSALOPActivationStatus(COVID19UpdateSALOPActivationStatus $request)
    {
        $validated = $request->validated();
        $case      = COVID19Case::find($validated['id']);
        $case->updateSALOPActivationStatus($validated['status_SALOP_activation']);
    }

    public function updateAMBActivationStatus(COVID19UpdateAMBActivationStatus $request)
    {
        $validated = $request->validated();
        $case      = COVID19Case::find($validated['id']);
        $case->updateAMBActivationStatus($validated['status_AMB_activation']);
    }

    public function updateBaseExitStatus(COVID19UpdateBaseExitStatus $request)
    {
        $validated = $request->validated();
        $case      = COVID19Case::find($validated['id']);
        $case->updateBaseExitStatus($validated['status_base_exit']);
    }

    public function updateArrivalOnSceneStatus(COVID19UpdateArrivalOnSceneStatus $request)
    {
        $validated = $request->validated();
        $case      = COVID19Case::find($validated['id']);
        $case->updateArrivalOnSceneStatus($validated['status_arrival_on_scene']);
    }

    public function updateDepartureFromSceneStatus(COVID19UpdateDepartureFromSceneStatus $request)
    {
        $validated = $request->validated();
        $case      = COVID19Case::find($validated['id']);
        $case->updateDepartureFromSceneStatus($validated['status_departure_from_scene']);
    }

    public function updateArrivalOnDestinationStatus(COVID19UpdateArrivalOnDestinationStatus $request)
    {
        $validated = $request->validated();
        $case      = COVID19Case::find($validated['id']);
        $case->updateArrivalOnDestinationStatus($validated['status_arrival_on_destination']);
    }

    public function updateDepartureFromDestinationStatus(COVID19UpdateDepartureFromDestinationStatus $request)
    {
        $validated = $request->validated();
        $case      = COVID19Case::find($validated['id']);
        $case->updateDepartureFromDestinationStatus($validated['status_departure_from_destination']);
    }

    public function updateBaseReturnStatus(COVID19UpdateBaseReturnStatus $request)
    {
        $validated = $request->validated();
        $case      = COVID19Case::find($validated['id']);
        $case->updateBaseReturnStatus($validated['status_base_return']);
    }

    public function updateAvailableStatus(COVID19UpdateAvailableStatus $request)
    {
        $validated = $request->validated();
        $case      = COVID19Case::find($validated['id']);
        $case->updateAvailableStatus($validated['status_available']);
    }

    public function cancel(COVID19CancelCase $request)
    {
        $validated     = $request->validated();
        $case          = COVID19Case::find($validated['id']);
        $old_ambulance = COVID19Ambulance::where('case_id', '=', $case->id)->get()->first();
        if ($old_ambulance) {
            $case->notify(new COVID19SlackNotification('*ATIVAÇÃO COVID-19 | '.$old_ambulance->structure.' ANULADA*'));
            $old_ambulance->INOP(null);
        }
        if (! $case->trashed()) {
            $case->delete();
        }
    }
}
