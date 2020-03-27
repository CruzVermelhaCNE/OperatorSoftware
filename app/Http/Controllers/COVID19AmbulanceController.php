<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\COVID19Ambulance;
use App\COVID19AmbulanceTeamMember;
use App\Http\Requests\COVID19AddContact;
use App\Http\Requests\COVID19GetTeamMember;
use App\Http\Requests\COVID19NewAmbulance;
use App\Http\Requests\COVID19RemoveContact;
use App\Http\Requests\COVID19UpdateAmbulanceActivePrevention;
use App\Http\Requests\COVID19UpdateAmbulanceRegion;
use App\Http\Requests\COVID19UpdateAmbulanceStatus;
use App\Http\Requests\COVID19UpdateAmbulanceStructure;
use App\Http\Requests\COVID19UpdateAmbulanceVehicleIdentification;

class COVID19AmbulanceController extends Controller
{
    public function newAmbulance(COVID19NewAmbulance $request)
    {
        $validated = $request->validated();
        COVID19Ambulance::createAmbulance($validated['structure'], $validated['region'], $validated['vehicle_identification'], 0, 0, $validated['active_prevention']);
    }

    public function getAmbulances()
    {
        $ambulances = COVID19Ambulance::all();
        /*foreach ($ambulances as $key => $ambulance) {
            $current_case = $ambulance->cases->where('status_available', '=', null)->last();
            if ($current_case) {
                if (! $current_case->trashed()) {
                    $ambulances[$key]->current_case = $current_case->case_id;
                } else {
                    $ambulances[$key]->current_case = null;
                }
            } else {
                $ambulance->current_case = null;
            }
        }*/
        dd($ambulances->toArray());
        return response()->json($ambulances);
    }

    public function getAmbulance($id)
    {
        $ambulance = COVID19Ambulance::find($id);
        return response()->json($ambulance);
    }

    public function getContacts($id)
    {
        $ambulance = COVID19Ambulance::find($id);
        return response()->json($ambulance->contacts);
    }

    public function getTeamMembers($id)
    {
        $team_members = COVID19AmbulanceTeamMember::where('ambulance_id', '=', $id)->get()->sortByDesc('id')->groupBy('name')->keys();
        return response()->json($team_members);
    }

    public function getTeamMember(COVID19GetTeamMember $request)
    {
        $validated = $request->validated();
        $team_member = COVID19AmbulanceTeamMember::where([
            ['ambulance_id','=',$validated['id']],
            ['name','=',$validated['name']],
        ])->get()->sortByDesc('id')->groupBy('name')->first();
        return response()->json($team_member);
    }

    public function INOP(COVID19UpdateAmbulanceStatus $request)
    {
        $validated = $request->validated();
        $ambulance = COVID19Ambulance::find($validated['id']);
        $ambulance->statusINOP(null);
    }

    public function available(COVID19UpdateAmbulanceStatus $request)
    {
        $validated = $request->validated();
        $ambulance = COVID19Ambulance::find($validated['id']);
        $ambulance->statusAvailable();
    }

    public function onBase(COVID19UpdateAmbulanceStatus $request)
    {
        $validated = $request->validated();
        $ambulance = COVID19Ambulance::find($validated['id']);
        $ambulance->statusOnBase();
    }

    public function baseExit(COVID19UpdateAmbulanceStatus $request)
    {
        $validated = $request->validated();
        $ambulance = COVID19Ambulance::find($validated['id']);
        $ambulance->statusBaseExit(null, null, null, null, null, null);
    }

    public function arrivalOnScene(COVID19UpdateAmbulanceStatus $request)
    {
        $validated = $request->validated();
        $ambulance = COVID19Ambulance::find($validated['id']);
        $ambulance->statusArrivalOnScene(null, null, null, null, null);
    }

    public function departureFromScene(COVID19UpdateAmbulanceStatus $request)
    {
        $validated = $request->validated();
        $ambulance = COVID19Ambulance::find($validated['id']);
        $ambulance->statusDepartureFromScene(null, null, null, null);
    }

    public function arrivalOnDestination(COVID19UpdateAmbulanceStatus $request)
    {
        $validated = $request->validated();
        $ambulance = COVID19Ambulance::find($validated['id']);
        $ambulance->statusArrivalOnDestination(null, null, null);
    }

    public function departureFromDestination(COVID19UpdateAmbulanceStatus $request)
    {
        $validated = $request->validated();
        $ambulance = COVID19Ambulance::find($validated['id']);
        $ambulance->statusDepartureFromDestination(null, null);
    }

    public function baseReturn(COVID19UpdateAmbulanceStatus $request)
    {
        $validated = $request->validated();
        $ambulance = COVID19Ambulance::find($validated['id']);
        $ambulance->statusBaseReturn(null);
    }

    public function updateStructure(COVID19UpdateAmbulanceStructure $request)
    {
        $validated = $request->validated();
        $ambulance = COVID19Ambulance::find($validated['id']);
        $ambulance->updateStructure($validated['structure']);
    }

    public function updateRegion(COVID19UpdateAmbulanceRegion $request)
    {
        $validated = $request->validated();
        $ambulance = COVID19Ambulance::find($validated['id']);
        $ambulance->updateRegion($validated['region']);
    }

    public function updateVehicleIdentification(COVID19UpdateAmbulanceVehicleIdentification $request)
    {
        $validated = $request->validated();
        $ambulance = COVID19Ambulance::find($validated['id']);
        $ambulance->updateVehicleIdentification($validated['vehicle_identification']);
    }

    public function updateActivePrevention(COVID19UpdateAmbulanceActivePrevention $request)
    {
        $validated = $request->validated();
        $ambulance = COVID19Ambulance::find($validated['id']);
        $ambulance->updateActivePrevention($validated['active_prevention']);
    }

    public function addContact(COVID19AddContact $request)
    {
        $validated = $request->validated();
        $ambulance = COVID19Ambulance::find($validated['id']);
        $ambulance->addContact($validated['contact'], $validated['name'], $validated['sms']);
    }

    public function removeContact(COVID19RemoveContact $request)
    {
        $validated = $request->validated();
        $ambulance = COVID19Ambulance::find($validated['id']);
        $ambulance->removeContact($validated['contact_id']);
    }
}
