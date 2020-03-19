<?php

namespace App;

use App\Events\COVID19AmbulanceSaved;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class COVID19Ambulance extends Model
{
    /** STATUS
     * 0: INOP
     * 1: DISPONIVEL
     * 2: NA BASE
     * 3: ACCIONAMENTO
     * 4: CAMINHO DO LOCAL
     * 5: NO LOCAL
     * 6: CAMINHO DO DESTINO
     * 7: NO DESTINO
     * 8: CAMINHO DA BASE DE DESINFECAO
     * 9: DESINFECÇÃO
     */


    protected $table = 'covid19_ambulances';

    protected $dispatchesEvents = [
        'saved' => COVID19AmbulanceSaved::class,
    ];

    public function case() {
        return $this->hasOne(COVID19Case::class,"id","case_id");
    }

    public function updater() {
        return $this->hasOne(User::class,'id','updated_by');
    }

    public static function createAmbulance($structure,$region,$vehicle_identification,$base_lat,$base_long,$active_prevention) {
        $amb = new COVID19Ambulance();
        $amb->structure = $structure;
        $amb->region = $region;
        $amb->vehicle_identification = $vehicle_identification;
        $amb->base_lat = $base_lat;
        $amb->base_long = $base_long;
        $amb->active_prevention = $active_prevention;
        $amb->status = 0;
        $amb->status_date = Carbon::now();
        $amb->updated_by = Auth::user()->id;
        $amb->save();
    }

    public function statusINOP($predicted_available) {
        if($this->case_id != null) {
            $this->case->statusAvailable(Carbon::now());
        }
        $this->case_id = null;
        $this->status = 0;
        $this->predicted_base_exit = null;
        $this->predicted_arrival_on_scene = null;
        $this->predicted_departure_from_scene = null;
        $this->predicted_arrival_on_destination = null;
        $this->predicted_departure_from_destination = null;
        $this->predicted_base_return = null;
        $this->predicted_available = $predicted_available;
        $this->updated_by = Auth::user()->id;
        $this->save();
    }

    public function statusAvailable() {
        if($this->case_id != null) {
            $this->case->statusAvailable(Carbon::now());
        }
        $this->case_id = null;
        $this->status = 1;
        $this->predicted_base_exit = null;
        $this->predicted_arrival_on_scene = null;
        $this->predicted_departure_from_scene = null;
        $this->predicted_arrival_on_destination = null;
        $this->predicted_departure_from_destination = null;
        $this->predicted_base_return = null;
        $this->predicted_available = null;
        $this->updated_by = Auth::user()->id;
        $this->save();
    }

    public function statusOnBase() {
        if($this->case_id != null) {
            $this->case->statusAvailable(Carbon::now());
        }
        $this->case_id = null;
        $this->status = 2;
        $this->predicted_base_exit = null;
        $this->predicted_arrival_on_scene = null;
        $this->predicted_departure_from_scene = null;
        $this->predicted_arrival_on_destination = null;
        $this->predicted_departure_from_destination = null;
        $this->predicted_base_return = null;
        $this->predicted_available = null;
        $this->updated_by = Auth::user()->id;
        $this->save();
    }


    public function activate($case_id,$predicted_base_exit,$predicted_arrival_on_scene,$predicted_departure_from_scene,$predicted_arrival_on_destination,$predicted_departure_from_destination,$predicted_base_return,$predicted_available) {
        $this->case_id = $case_id;
        $this->status = 3;
        $this->predicted_base_exit = $predicted_base_exit;
        $this->predicted_arrival_on_scene = $predicted_arrival_on_scene;
        $this->predicted_departure_from_scene = $predicted_departure_from_scene;
        $this->predicted_arrival_on_destination = $predicted_arrival_on_destination;
        $this->predicted_departure_from_destination = $predicted_departure_from_destination;
        $this->predicted_base_return = $predicted_base_return;
        $this->predicted_available = $predicted_available;
        $this->updated_by = Auth::user()->id;
        $this->save();
        $this->case->addVehicleInformation($this->structure, $this->vehicle_identification, 1);
        $this->case->statusActivation(Carbon::now());
    }

    public function statusBaseExit($predicted_arrival_on_scene,$predicted_departure_from_scene,$predicted_arrival_on_destination,$predicted_departure_from_destination,$predicted_base_return,$predicted_available) {
        $this->status = 4;
        $this->predicted_arrival_on_scene = $predicted_arrival_on_scene;
        $this->predicted_departure_from_scene = $predicted_departure_from_scene;
        $this->predicted_arrival_on_destination = $predicted_arrival_on_destination;
        $this->predicted_departure_from_destination = $predicted_departure_from_destination;
        $this->predicted_base_return = $predicted_base_return;
        $this->predicted_available = $predicted_available;
        $this->updated_by = Auth::user()->id;
        $this->save();
        $this->case->statusBaseExit(Carbon::now());
    }

    public function statusArrivalOnScene($predicted_departure_from_scene,$predicted_arrival_on_destination,$predicted_departure_from_destination,$predicted_base_return,$predicted_available) {
        $this->status = 5;
        $this->predicted_departure_from_scene = $predicted_departure_from_scene;
        $this->predicted_arrival_on_destination = $predicted_arrival_on_destination;
        $this->predicted_departure_from_destination = $predicted_departure_from_destination;
        $this->predicted_base_return = $predicted_base_return;
        $this->predicted_available = $predicted_available;
        $this->updated_by = Auth::user()->id;
        $this->save();
        $this->case->statusArrivalOnScene(Carbon::now());
    }

    public function statusDepartureFromScene($predicted_arrival_on_destination,$predicted_departure_from_destination,$predicted_base_return,$predicted_available) {
        $this->status = 6;
        $this->predicted_arrival_on_destination = $predicted_arrival_on_destination;
        $this->predicted_departure_from_destination = $predicted_departure_from_destination;
        $this->predicted_base_return = $predicted_base_return;
        $this->predicted_available = $predicted_available;
        $this->updated_by = Auth::user()->id;
        $this->save();
        $this->case->statusDepartureFromScene(Carbon::now());
    }

    public function statusArrivalOnDestination($predicted_departure_from_destination,$predicted_base_return,$predicted_available) {
        $this->status = 7;
        $this->predicted_departure_from_destination = $predicted_departure_from_destination;
        $this->predicted_base_return = $predicted_base_return;
        $this->predicted_available = $predicted_available;
        $this->updated_by = Auth::user()->id;
        $this->save();
        $this->case->statusArrivalOnDestination(Carbon::now());
    }

    public function statusDepartureFromDestination($predicted_base_return,$predicted_available) {
        $this->status = 8;
        $this->predicted_base_return = $predicted_base_return;
        $this->predicted_available = $predicted_available;
        $this->updated_by = Auth::user()->id;
        $this->save();
        $this->case->statusDepartureFromDestination(Carbon::now());
    }

    public function statusBaseReturn($predicted_available) {
        $this->status = 9;
        $this->predicted_available = $predicted_available;
        $this->updated_by = Auth::user()->id;
        $this->save();
        $this->case->statusBaseReturn(Carbon::now());
    }

    public function updateVehicleIdentification($vehicle_identification) {
        $this->vehicle_identification = $vehicle_identification;
        $this->updated_by = Auth::user()->id;
        $this->save();
    }

    public function updateStructure($structure) {
        $this->structure = $structure;
        $this->updated_by = Auth::user()->id;
        $this->save();
    }

    public function updateRegion($region) {
        $this->region = $region;
        $this->updated_by = Auth::user()->id;
        $this->save();
    }

    public function updateBaseLocalization($base_lat,$base_long) {
        $this->base_lat = $base_lat;
        $this->base_long = $base_long;
        $this->updated_by = Auth::user()->id;
        $this->save();
    }

    public function updateActivePrevention($active_prevention) {
        $this->active_prevention = $active_prevention;
        $this->updated_by = Auth::user()->id;
        $this->save();
    }    
}
