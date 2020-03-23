<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\COVID19Ambulance;
use App\COVID19Case;
use App\COVID19CaseObservation;
use App\Http\Requests\COVID19AddObservation;
use App\Http\Requests\COVID19CancelCase;
use App\Http\Requests\COVID19InsertAmbulance;
use App\Http\Requests\COVID19InsertEvent;
use App\Http\Requests\COVID19InsertPatient;
use App\Http\Requests\COVID19InsertSIEMAmbulance;
use App\Http\Requests\COVID19InsertTeamMember;
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
use App\Http\Requests\COVID19UpdateCounty;
use App\Http\Requests\COVID19UpdateDepartureFromDestinationStatus;
use App\Http\Requests\COVID19UpdateDepartureFromSceneStatus;
use App\Http\Requests\COVID19UpdateDestination;
use App\Http\Requests\COVID19UpdateDistrict;
use App\Http\Requests\COVID19UpdateDoctorResponsibleOnDestination;
use App\Http\Requests\COVID19UpdateDoctorResponsibleOnScene;
use App\Http\Requests\COVID19UpdateNotes;
use App\Http\Requests\COVID19UpdateOnSceneUnits;
use App\Http\Requests\COVID19UpdateParish;
use App\Http\Requests\COVID19UpdateRef;
use App\Http\Requests\COVID19UpdateSALOPActivationStatus;
use App\Http\Requests\COVID19UpdateSource;
use App\Http\Requests\COVID19UpdateStreet;
use App\Http\Requests\COVID19UpdateTotalDistance;
use App\Http\Requests\COVID19RemoveObservation;
use App\Http\Requests\COVID19RemovePatient;
use App\Http\Requests\COVID19RemoveTeamMember;
use App\Http\Requests\COVID19UpdatePatientConfirmed;
use App\Http\Requests\COVID19UpdatePatientDoB;
use App\Http\Requests\COVID19UpdatePatientFirstname;
use App\Http\Requests\COVID19UpdatePatientInvasiveCare;
use App\Http\Requests\COVID19UpdatePatientLastname;
use App\Http\Requests\COVID19UpdatePatientRNU;
use App\Http\Requests\COVID19UpdatePatientSex;
use App\Http\Requests\COVID19UpdatePatientSuspect;
use App\Http\Requests\COVID19UpdatePatientSuspectValidation;
use App\Http\Requests\COVID19UpdateTeamMemberAge;
use App\Http\Requests\COVID19UpdateTeamMemberContact;
use App\Http\Requests\COVID19UpdateTeamMemberName;
use App\Http\Requests\COVID19UpdateTeamMemberType;
use App\Notifications\COVID19AmbulanceSlackNotification;
use App\Notifications\COVID19AmbulanceNexmoNotification;
use Carbon\Carbon;
use Notification;
use Illuminate\Support\Facades\Auth;

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

    public function getPatients($id)
    {
        $case = COVID19Case::find($id);
        $patients = $case->patients;
        return response()->json($patients);
    }

    public function getTeamMembers($id)
    {
        $case = COVID19Case::find($id);
        $team_members = $case->team_members;
        return response()->json($team_members);
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
        $observations = $case->observations;
        foreach ($observations as $key => $observation) {
            $observations[$key]->author_name = $observation->author->name;
        }
        return response()->json($observations);
    }

    public function insertPatient(COVID19InsertPatient $request)
    {
        $validated = $request->validated();
        $case      = COVID19Case::find($validated['id']);
        $case->addPatient($validated['RNU'], $validated['firstname'], $validated['lastname'], $validated['sex'], $validated['DoB'], $validated['suspect'], $validated['suspect_validation'], $validated['confirmed'], $validated['invasive_care']);
    }

    public function insertEvent(COVID19InsertEvent $request)
    {
        $validated = $request->validated();
        $case      = COVID19Case::find($validated['id']);
        $case->addEventInformation($validated['street'], $validated['parish'], $validated['county'], $validated['district'], $validated['ref'], $validated['source'], null, null, $validated['destination'], null, null, $validated['doctor_responsible_on_scene'], $validated['doctor_responsible_on_destination'], $validated['on_scene_units'], $validated['total_distance']);
    }

    public function insertTeamMember(COVID19InsertTeamMember $request)
    {
        $validated = $request->validated();
        $case      = COVID19Case::find($validated['id']);
        $case->addTeamMember($validated['name'], $validated['age'], $validated['contact'], $validated['type']);
    }

    public function removeTeamMember(COVID19RemoveTeamMember $request)
    {
        $validated = $request->validated();
        $case      = COVID19Case::find($validated['id']);
        $case->removeTeamMember($validated['team_member_id']);
    }

    public function insertAmbulance(COVID19InsertAmbulance $request)
    {
        $validated     = $request->validated();
        $case          = COVID19Case::find($validated['id']);
        $ambulance     = COVID19Ambulance::find($validated['ambulance_id']);
        $old_ambulance = COVID19Ambulance::where('case_id', '=', $case->id)->get();
        if ($old_ambulance->count() == 1) {
            $old_ambulance->first()->statusINOP(null);
            $message = "*ATIVAÇÃO COVID-19 | ".$old_ambulance->structure." ANULADA*";
            $old_ambulance->notify(new COVID19AmbulanceSlackNotification($message));
            Notification::send($old_ambulance->contacts()->where('sms','=',true)->get(), new COVID19AmbulanceNexmoNotification($message));
        }
        $ambulance->activate($case->id, null, null, null, null, null, null, null);
        $message = "*ATIVAÇÃO COVID-19 | ".$ambulance->structure."*\nOrigem: ".$case->complete_source()."\n".$case->source."\nDestino: ".$case->destination."\nCODU: ".($case->CODU_number == null? "Sem Número": $case->CODU_number);
        $ambulance->notify(new COVID19AmbulanceSlackNotification($message));
        Notification::send($ambulance->contacts()->where('sms','=',true)->get(), new COVID19AmbulanceNexmoNotification($message));
    }

    public function insertSIEMAmbulance(COVID19InsertSIEMAmbulance $request)
    {
        $validated     = $request->validated();
        $case          = COVID19Case::find($validated['id']);
        $old_ambulance = COVID19Ambulance::where('case_id', '=', $case->id)->get();
        if ($old_ambulance->count() == 1) {
            $old_ambulance->first()->INOP(null);
            $message = "*ATIVAÇÃO COVID-19 | '.$old_ambulance->structure.' ANULADA*";
            $old_ambulance->notify(new COVID19AmbulanceSlackNotification($message));
            Notification::send($old_ambulance->contacts()->where('sms','=',true)->get(), new COVID19AmbulanceNexmoNotification($message));
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

    public function updatePatientRNU(COVID19UpdatePatientRNU $request)
    {
        $validated = $request->validated();
        $case      = COVID19Case::find($validated['id']);
        $case->updatePatientRNU($validated['patient_id'],$validated['rnu']);
    }

    public function updatePatientFirstName(COVID19UpdatePatientFirstname $request)
    {
        $validated = $request->validated();
        $case      = COVID19Case::find($validated['id']);
        $case->updatePatientFirstname($validated['patient_id'],$validated['firstname']);
    }

    public function updatePatientLastname(COVID19UpdatePatientLastname $request)
    {
        $validated = $request->validated();
        $case      = COVID19Case::find($validated['id']);
        $case->updatePatientLastname($validated['patient_id'],$validated['lastname']);
    }

    public function updatePatientSex(COVID19UpdatePatientSex $request)
    {
        $validated = $request->validated();
        $case      = COVID19Case::find($validated['id']);
        $case->updatePatientSex($validated['patient_id'],$validated['sex']);
    }

    public function updatePatientDoB(COVID19UpdatePatientDoB $request)
    {
        $validated = $request->validated();
        $case      = COVID19Case::find($validated['id']);
        $case->updatePatientDoB($validated['patient_id'],$validated['dob']);
    }

    public function updatePatientSuspect(COVID19UpdatePatientSuspect $request)
    {
        $validated = $request->validated();
        $case      = COVID19Case::find($validated['id']);
        $case->updatePatientSuspect($validated['patient_id'],$validated['suspect']);
    }

    public function updatePatientSuspectValidation(COVID19UpdatePatientSuspectValidation $request)
    {
        $validated = $request->validated();
        $case      = COVID19Case::find($validated['id']);
        $case->updatePatientSuspectValidation($validated['patient_id'],$validated['suspect_validation']);
    }

    public function updateConfirmed(COVID19UpdatePatientConfirmed $request)
    {
        $validated = $request->validated();
        $case      = COVID19Case::find($validated['id']);
        $case->updatePatientConfirmed($validated['patient_id'],$validated['confirmed']);
    }

    public function updatePatientInvasiveCare(COVID19UpdatePatientInvasiveCare $request)
    {
        $validated = $request->validated();
        $case      = COVID19Case::find($validated['id']);
        $case->updatePatientInvasiveCare($validated['patient_id'],$validated['invasive_care']);
    }

    public function removePatient(COVID19RemovePatient $request)
    {
        $validated = $request->validated();
        $case      = COVID19Case::find($validated['id']);
        $case->removePatient($validated['patient_id']);
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

    public function updateTeamMemberName(COVID19UpdateTeamMemberName $request)
    {
        $validated = $request->validated();
        $case      = COVID19Case::find($validated['id']);
        $case->updateTeamMemberName($validated['team_member_id'],$validated['name']);
    }

    public function updateTeamMemberAge(COVID19UpdateTeamMemberAge $request)
    {
        $validated = $request->validated();
        $case      = COVID19Case::find($validated['id']);
        $case->updateTeamMemberAge($validated['team_member_id'],$validated['age']);
    }

    public function updateTeamMemberContact(COVID19UpdateTeamMemberContact $request)
    {
        $validated = $request->validated();
        $case      = COVID19Case::find($validated['id']);
        $case->COVID19UpdateTeamMemberContact($validated['team_member_id'],$validated['contact']);
    }

    public function updateTeamMemberType(COVID19UpdateTeamMemberType $request)
    {
        $validated = $request->validated();
        $case      = COVID19Case::find($validated['id']);
        $case->updateTeamMemberType($validated['team_member_id'],$validated['type']);
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

    public function updateNotes(COVID19UpdateNotes $request)
    {
        $validated = $request->validated();
        $case      = COVID19Case::find($validated['id']);
        $case->updateNotes($validated['notes']);
    }

    public function addObservation(COVID19AddObservation $request)
    {
        $validated = $request->validated();
        $case      = COVID19Case::find($validated['id']);
        $case->addObservation($validated['observation']);
    }

    public function removeObservation(COVID19RemoveObservation $request)
    {
        $validated = $request->validated();
        $case      = COVID19Case::find($validated['id']);
        $case->removeObservation($validated['observation_id']);
    }

    public function cancel(COVID19CancelCase $request)
    {
        $validated     = $request->validated();
        $case          = COVID19Case::find($validated['id']);
        $old_ambulance = COVID19Ambulance::where('case_id', '=', $case->id)->get()->first();
        if ($old_ambulance) {
            $message = "*ATIVAÇÃO COVID-19 | '.$old_ambulance->structure.' ANULADA*";
            $old_ambulance->notify(new COVID19AmbulanceSlackNotification($message));
            Notification::send($old_ambulance->contacts()->where('sms','=',true)->get(), new COVID19AmbulanceNexmoNotification($message));
            $old_ambulance->INOP(null);
        }
        if (! $case->trashed()) {
            $case->delete();
        }
    }
}
