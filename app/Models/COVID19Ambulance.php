<?php
declare(strict_types=1);

namespace App\Models;

use App\Events\COVID19AmbulanceSaved;
use App\Events\COVID19UpdateAmbulance;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;

class COVID19Ambulance extends Model
{
    use Notifiable;
    use SoftDeletes;

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

    protected $appends = ['current_case'];

    protected $dispatchesEvents = [
        'saved' => COVID19AmbulanceSaved::class,
    ];

    public function routeNotificationForSlack()
    {
        return env('SLACK_WEBHOOK_URL');
    }

    public function routeNotificationForNexmo($notification)
    {
        return $this->contacts->first()->formatNumber();
    }

    public function case()
    {
        return $this->hasOne(COVID19Case::class, 'id', 'case_id');
    }

    public function updater()
    {
        return $this->hasOne(User::class, 'id', 'updated_by');
    }

    public function contacts()
    {
        return $this->hasMany(COVID19AmbulanceContact::class, 'ambulance_id', 'id');
    }

    public function cases()
    {
        return $this->hasMany(COVID19AmbulanceCase::class, 'ambulance_id', 'id');
    }

    public function getCurrentCaseAttribute()
    {
        $current_case = $this->cases->where('status_available', '=', null)->last();
        if ($current_case) {
            if (! $current_case->trashed()) {
                $current_case = $current_case->case_id;
            } else {
                $current_case = null;
            }
        } else {
            $current_case = null;
        }
        return $current_case;
    }

    public function forceUpdate()
    {
        event(new COVID19UpdateAmbulance($this));
    }

    public function addContact($contact, $name, $sms)
    {
        $ambulance_id = $this->id;
        COVID19AmbulanceContact::createContact($ambulance_id, $contact, $name, $sms);
        $this->forceUpdate();
    }

    public function removeContact($contact_id)
    {
        $contact = COVID19AmbulanceContact::find($contact_id);
        $contact->delete();
        $this->forceUpdate();
    }

    public static function createAmbulance($structure, $region, $vehicle_identification, $base_lat, $base_long, $active_prevention)
    {
        $amb                         = new COVID19Ambulance();
        $amb->structure              = $structure;
        $amb->region                 = $region;
        $amb->vehicle_identification = $vehicle_identification;
        $amb->base_lat               = $base_lat;
        $amb->base_long              = $base_long;
        $amb->active_prevention      = $active_prevention;
        $amb->status                 = 0;
        $amb->status_date            = Carbon::now();
        $amb->updated_by             = Auth::user()->id;
        $amb->save();
    }

    public function statusINOP($predicted_available)
    {
        if ($this->case_id != null) {
            $this->case->statusAvailable(Carbon::now());
        }
        $this->case_id                              = null;
        $this->status                               = 0;
        $this->status_date                          = Carbon::now();
        $this->predicted_base_exit                  = null;
        $this->predicted_arrival_on_scene           = null;
        $this->predicted_departure_from_scene       = null;
        $this->predicted_arrival_on_destination     = null;
        $this->predicted_departure_from_destination = null;
        $this->predicted_base_return                = null;
        $this->predicted_available                  = $predicted_available;
        $this->updated_by                           = Auth::user()->id;
        $this->save();
    }

    public function statusAvailable()
    {
        if ($this->case_id != null) {
            $this->case->statusAvailable(Carbon::now());
        }
        $this->case_id                              = null;
        $this->status                               = 1;
        $this->status_date                          = Carbon::now();
        $this->predicted_base_exit                  = null;
        $this->predicted_arrival_on_scene           = null;
        $this->predicted_departure_from_scene       = null;
        $this->predicted_arrival_on_destination     = null;
        $this->predicted_departure_from_destination = null;
        $this->predicted_base_return                = null;
        $this->predicted_available                  = null;
        $this->updated_by                           = Auth::user()->id;
        $this->save();
    }

    public function statusOnBase()
    {
        if ($this->case_id != null) {
            $this->case->statusAvailable(Carbon::now());
        }
        $this->case_id                              = null;
        $this->status                               = 2;
        $this->status_date                          = Carbon::now();
        $this->predicted_base_exit                  = null;
        $this->predicted_arrival_on_scene           = null;
        $this->predicted_departure_from_scene       = null;
        $this->predicted_arrival_on_destination     = null;
        $this->predicted_departure_from_destination = null;
        $this->predicted_base_return                = null;
        $this->predicted_available                  = null;
        $this->updated_by                           = Auth::user()->id;
        $this->save();
    }

    public function activate($case_id, $predicted_base_exit, $predicted_arrival_on_scene, $predicted_departure_from_scene, $predicted_arrival_on_destination, $predicted_departure_from_destination, $predicted_base_return, $predicted_available)
    {
        $this->case_id                              = $case_id;
        $this->status                               = 3;
        $this->status_date                          = Carbon::now();
        $this->predicted_base_exit                  = $predicted_base_exit;
        $this->predicted_arrival_on_scene           = $predicted_arrival_on_scene;
        $this->predicted_departure_from_scene       = $predicted_departure_from_scene;
        $this->predicted_arrival_on_destination     = $predicted_arrival_on_destination;
        $this->predicted_departure_from_destination = $predicted_departure_from_destination;
        $this->predicted_base_return                = $predicted_base_return;
        $this->predicted_available                  = $predicted_available;
        $this->updated_by                           = Auth::user()->id;
        $this->save();
        COVID19AmbulanceCase::createAmbulanceCase($this->id, $case_id);
        $this->case->addVehicleInformation($this->structure, $this->vehicle_identification, 1);
        $this->case->statusActivation(Carbon::now());
        $this->forceUpdate();
    }

    public function statusBaseExit($predicted_arrival_on_scene, $predicted_departure_from_scene, $predicted_arrival_on_destination, $predicted_departure_from_destination, $predicted_base_return, $predicted_available)
    {
        $this->status                               = 4;
        $this->status_date                          = Carbon::now();
        $this->predicted_arrival_on_scene           = $predicted_arrival_on_scene;
        $this->predicted_departure_from_scene       = $predicted_departure_from_scene;
        $this->predicted_arrival_on_destination     = $predicted_arrival_on_destination;
        $this->predicted_departure_from_destination = $predicted_departure_from_destination;
        $this->predicted_base_return                = $predicted_base_return;
        $this->predicted_available                  = $predicted_available;
        $this->updated_by                           = Auth::user()->id;
        $this->save();
        $this->case->statusBaseExit(Carbon::now());
        $this->case->statusArrivalOnScene(null);
        $this->case->statusDepartureFromScene(null);
        $this->case->statusArrivalOnDestination(null);
        $this->case->statusDepartureFromDestination(null);
        $this->case->statusBaseReturn(null);
    }

    public function statusArrivalOnScene($predicted_departure_from_scene, $predicted_arrival_on_destination, $predicted_departure_from_destination, $predicted_base_return, $predicted_available)
    {
        $this->status                               = 5;
        $this->status_date                          = Carbon::now();
        $this->predicted_departure_from_scene       = $predicted_departure_from_scene;
        $this->predicted_arrival_on_destination     = $predicted_arrival_on_destination;
        $this->predicted_departure_from_destination = $predicted_departure_from_destination;
        $this->predicted_base_return                = $predicted_base_return;
        $this->predicted_available                  = $predicted_available;
        $this->updated_by                           = Auth::user()->id;
        $this->save();
        $this->case->statusArrivalOnScene(Carbon::now());
        $this->case->statusDepartureFromScene(null);
        $this->case->statusArrivalOnDestination(null);
        $this->case->statusDepartureFromDestination(null);
        $this->case->statusBaseReturn(null);
    }

    public function statusDepartureFromScene($predicted_arrival_on_destination, $predicted_departure_from_destination, $predicted_base_return, $predicted_available)
    {
        $this->status                               = 6;
        $this->status_date                          = Carbon::now();
        $this->predicted_arrival_on_destination     = $predicted_arrival_on_destination;
        $this->predicted_departure_from_destination = $predicted_departure_from_destination;
        $this->predicted_base_return                = $predicted_base_return;
        $this->predicted_available                  = $predicted_available;
        $this->updated_by                           = Auth::user()->id;
        $this->save();
        $this->case->statusDepartureFromScene(Carbon::now());
        $this->case->statusArrivalOnDestination(null);
        $this->case->statusDepartureFromDestination(null);
        $this->case->statusBaseReturn(null);
    }

    public function statusArrivalOnDestination($predicted_departure_from_destination, $predicted_base_return, $predicted_available)
    {
        $this->status                               = 7;
        $this->status_date                          = Carbon::now();
        $this->predicted_departure_from_destination = $predicted_departure_from_destination;
        $this->predicted_base_return                = $predicted_base_return;
        $this->predicted_available                  = $predicted_available;
        $this->updated_by                           = Auth::user()->id;
        $this->save();
        $this->case->statusArrivalOnDestination(Carbon::now());
        $this->case->statusDepartureFromDestination(null);
        $this->case->statusBaseReturn(null);
    }

    public function statusDepartureFromDestination($predicted_base_return, $predicted_available)
    {
        $this->status                = 8;
        $this->status_date           = Carbon::now();
        $this->predicted_base_return = $predicted_base_return;
        $this->predicted_available   = $predicted_available;
        $this->updated_by            = Auth::user()->id;
        $this->save();
        $this->case->statusDepartureFromDestination(Carbon::now());
        $this->case->statusBaseReturn(null);
    }

    public function statusBaseReturn($predicted_available)
    {
        $this->status              = 9;
        $this->status_date         = Carbon::now();
        $this->predicted_available = $predicted_available;
        $this->updated_by          = Auth::user()->id;
        $this->save();
        $this->case->statusBaseReturn(Carbon::now());
    }

    public function updateVehicleIdentification($vehicle_identification)
    {
        $this->vehicle_identification = $vehicle_identification;
        $this->updated_by             = Auth::user()->id;
        $this->save();
    }

    public function updateStructure($structure)
    {
        $this->structure  = $structure;
        $this->updated_by = Auth::user()->id;
        $this->save();
    }

    public function updateRegion($region)
    {
        $this->region     = $region;
        $this->updated_by = Auth::user()->id;
        $this->save();
    }

    public function updateBaseLocalization($base_lat, $base_long)
    {
        $this->base_lat   = $base_lat;
        $this->base_long  = $base_long;
        $this->updated_by = Auth::user()->id;
        $this->save();
    }

    public function updateActivePrevention($active_prevention)
    {
        $this->active_prevention = $active_prevention;
        $this->updated_by        = Auth::user()->id;
        $this->save();
    }
}
