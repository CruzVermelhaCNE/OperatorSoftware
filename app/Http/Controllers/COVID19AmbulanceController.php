<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\COVID19Ambulance;
use App\Http\Requests\COVID19NewAmbulance;
use App\Http\Requests\COVID19UpdateAmbulanceActivePrevention;
use App\Http\Requests\COVID19UpdateAmbulanceRegion;
use App\Http\Requests\COVID19UpdateAmbulanceStatus;
use App\Http\Requests\COVID19UpdateAmbulanceStructure;

class COVID19AmbulanceController extends Controller
{
    public function newAmbulance(COVID19NewAmbulance $request)
    {
        $validated = $request->validated();
        COVID19Ambulance::createAmbulance($validated["structure"],$validated["region"],$validated["vehicle_identification"],0,0,$validated["active_prevention"]);
    }

    public function getAmbulances() {
        $ambulances = COVID19Ambulance::all();
        return response()->json($ambulances);
    }

    public function getAmbulance($id) {
        $ambulance = COVID19Ambulance::find($id);
        return response()->json($ambulance);
    }

    public function INOP(COVID19UpdateAmbulanceStatus $request) {
        $validated = $request->validated();
        $ambulance = COVID19Ambulance::find($validated["id"]);
        $ambulance->statusINOP(null);
    }

    public function available(COVID19UpdateAmbulanceStatus $request) {
        $validated = $request->validated();
        $ambulance = COVID19Ambulance::find($validated["id"]);
        $ambulance->statusAvailable();
    }

    public function onBase(COVID19UpdateAmbulanceStatus $request) {
        $validated = $request->validated();
        $ambulance = COVID19Ambulance::find($validated["id"]);
        $ambulance->statusOnBase();
    }

    public function baseExit(COVID19UpdateAmbulanceStatus $request) {
        $validated = $request->validated();
        $ambulance = COVID19Ambulance::find($validated["id"]);
        $ambulance->statusBaseExit(null,null,null,null,null,null);
    }
    public function arrivalOnScene(COVID19UpdateAmbulanceStatus $request) {
        $validated = $request->validated();
        $ambulance = COVID19Ambulance::find($validated["id"]);
        $ambulance->statusArrivalOnScene(null,null,null,null,null);
    }
    public function departureFromScene(COVID19UpdateAmbulanceStatus $request) {
        $validated = $request->validated();
        $ambulance = COVID19Ambulance::find($validated["id"]);
        $ambulance->statusDepartureFromScene(null,null,null,null);
    }
    public function arrivalOnDestination(COVID19UpdateAmbulanceStatus $request) {
        $validated = $request->validated();
        $ambulance = COVID19Ambulance::find($validated["id"]);
        $ambulance->statusArrivalOnDestination(null,null,null);
    }
    public function departureFromDestination(COVID19UpdateAmbulanceStatus $request) {
        $validated = $request->validated();
        $ambulance = COVID19Ambulance::find($validated["id"]);
        $ambulance->statusDepartureFromDestination(null,null);
    }
    public function baseReturn(COVID19UpdateAmbulanceStatus $request) {
        $validated = $request->validated();
        $ambulance = COVID19Ambulance::find($validated["id"]);
        $ambulance->statusBaseReturn(null);
    }

    public function updateStructure(COVID19UpdateAmbulanceStructure $request) {
        $validated = $request->validated();
        $ambulance = COVID19Ambulance::find($validated["id"]);
        $ambulance->updateStructure($validated["structure"]);
    }

    public function updateRegion(COVID19UpdateAmbulanceRegion $request) {
        $validated = $request->validated();
        $ambulance = COVID19Ambulance::find($validated["id"]);
        $ambulance->updateRegion($validated["region"]);
    }

    public function updateVehicleIdentification(COVID19UpdateAmbulanceRegion $request) {
        $validated = $request->validated();
        $ambulance = COVID19Ambulance::find($validated["id"]);
        $ambulance->updateVehicleIdentification($validated["vehicle_idenntification"]);
    }

    public function updateActivePrevention(COVID19UpdateAmbulanceActivePrevention $request) {
        $validated = $request->validated();
        $ambulance = COVID19Ambulance::find($validated["id"]);
        $ambulance->updateActivePrevention($validated["active_prevention"]);
    }
}