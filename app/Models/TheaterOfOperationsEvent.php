
<?php
declare(strict_types=1);

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class TheaterOfOperationsEvent extends Model
{
    public const STATUS_CANCELED      = 0;
    public const STATUS_DISPATCH      = 1;
    public const STATUS_ON_GOING      = 2;
    public const STATUS_IN_CONCLUSION = 3;
    public const STATUS_FINISHED      = 4;

    private const CACHE_BRIEF_TIMETAPE = 'TheaterOfOperations_Events_Brief_TimeTape_';
    private const CACHE_VICTIMS        = 'TheaterOfOperations_Events_Victims_';

    private static function getStatusTextFromNumber($status)
    {
        switch ($status) {
            case self::STATUS_CANCELED:
                return 'Anulada';
            case self::STATUS_DISPATCH:
                return 'Dispacho';
            case self::STATUS_ON_GOING:
                return 'A Decorrer';
            case self::STATUS_IN_CONCLUSION:
                return 'Em Conclusão';
            case self::STATUS_FINISHED:
                return 'Terminada';
        }
    }

    public static function create($codu, $cdos, $type, $status, $observations, $location, $lat, $long, $theater_of_operations_id, $theater_of_operations_sector_id)
    {
        if ($theater_of_operations_id == null && $theater_of_operations_sector_id == null) {
            throw new Exception('Major Event ID or Major Event Sector ID must be defined');
        }
        $theater_of_operations_event                                  = new TheaterOfOperationsEvent();
        $theater_of_operations_event->type                            = $type;
        $theater_of_operations_event->codu                            = $codu;
        $theater_of_operations_event->cdos                            = $cdos;
        $theater_of_operations_event->status                          = $status;
        $theater_of_operations_event->observations                    = $observations;
        $theater_of_operations_event->location                        = $location;
        $theater_of_operations_event->lat                             = $lat;
        $theater_of_operations_event->long                            = $long;
        $theater_of_operations_event->theater_of_operations_id        = $theater_of_operations_id;
        $theater_of_operations_event->theater_of_operations_sector_id = $theater_of_operations_sector_id;
        $theater_of_operations_event->save();
        TheaterOfOperationsTimeTape::create('Ocorrência (#'.$theater_of_operations_event->id.'): Ocorrência de tipo '.$type.' criada', $theater_of_operations_event->theater_of_operations_id, $theater_of_operations_event->theater_of_operations_sector_id, TheaterOfOperationsTimeTape::TYPE_CREATION_DELETION);
        return $theater_of_operations_event;
    }

    public function generateBriefTimeTape()
    {
        $timetape = $this->theater_of_operations->time_tape()->where('description', 'LIKE', 'Ocorrência (#'.$this->id.'):%')->orderby('id', 'DESC')->limit(10)->get();
        $array    = [];
        foreach ($timetape as $timetape_entry) {
            $array[] = [
                $timetape_entry->date,
                $timetape_entry->description,
            ];
        }
        $data = \json_encode($array);
        Cache::put(self::CACHE_BRIEF_TIMETAPE.$this->id, $data);
        return $data;
    }

    public function resetBriefTimeTape()
    {
        if (Cache::has(self::CACHE_BRIEF_TIMETAPE.$this->id)) {
            Cache::pull(self::CACHE_BRIEF_TIMETAPE.$this->id);
        }
    }

    public function getBriefTimeTape()
    {
        $data = '';
        if (Cache::has(self::CACHE_BRIEF_TIMETAPE.$this->id)) {
            $data = Cache::get(self::CACHE_BRIEF_TIMETAPE.$this->id);
        } else {
            $data = $this->generateBriefTimeTape();
        }
        return $data;
    }

    public function generateVictims()
    {
        $victims = $this->victims;
        $array   = [];
        foreach ($victims as $victim) {
            $array[] = [
                $victim->status_text,
                $victim->name?$victim->name:'N/A',
                $victim->age?$victim->age:'N/A',
                $victim->destination?$victim->destination:'N/A',
                $victim->lat,
                $victim->long,
                $victim->id,
            ];
        }
        $data = \json_encode($array);
        Cache::put(self::CACHE_VICTIMS.$this->id, $data);
        return $data;
    }

    public function resetVictims()
    {
        if (Cache::has(self::CACHE_VICTIMS.$this->id)) {
            Cache::pull(self::CACHE_VICTIMS.$this->id);
        }
    }

    public function getVictims()
    {
        $data = '';
        if (Cache::has(self::CACHE_VICTIMS.$this->id)) {
            $data = Cache::get(self::CACHE_VICTIMS.$this->id);
        } else {
            $data = $this->generateVictims();
        }
        return $data;
    }

    public function getUnits()
    {
        $units = $this->units;
        $array = [];
        foreach ($units as $unit) {
            $array[] = [
                $unit->type,
                $unit->tail_number?$unit->tail_number:'N/A',
                $unit->plate?$unit->plate:'N/A',
                $unit->status_text,
                $unit->status,
                $unit->lat,
                $unit->long,
                $this->event_units()->where('theater_of_operations_unit_id', '=', $unit->id)->get()->first()->id,
            ];
        };
        $data = \json_encode($array);
        return $data;
    }

    public function getStatusTextAttribute()
    {
        return self::getStatusTextFromNumber($this->status);
    }

    public function getUnitsAttribute()
    {
        $units       = collect();
        $event_units = $this->event_units;
        foreach ($event_units as $event_unit) {
            $units->push($event_unit->unit);
        }
        return $units;
    }

    public function isFinished()
    {
        return $this->status == self::STATUS_CANCELED || $this->status == self::STATUS_FINISHED;
    }

    public function updateCODU($codu)
    {
        $old = $this->codu;
        if ($old == null) {
            $old = 'N/A';
        }
        $this->codu = $codu;
        $this->save();
        TheaterOfOperationsTimeTape::create('Ocorrência (#'.$this->id.'): Nº CODU atualizado de '.$old.' para '.$codu, $this->theater_of_operations_id, $this->theater_of_operations_sector_id);
        $this->resetBriefTimeTape();
        return $this;
    }

    public function updateCDOS($cdos)
    {
        $old = $this->cdos;
        if ($old == null) {
            $old = 'N/A';
        }
        $this->cdos = $cdos;
        $this->save();
        TheaterOfOperationsTimeTape::create('Ocorrência (#'.$this->id.'): Nº CDOS atualizado de '.$old.' para '.$cdos, $this->theater_of_operations_id, $this->theater_of_operations_sector_id);
        $this->resetBriefTimeTape();
        return $this;
    }

    public function updateType($type)
    {
        $old        = $this->type;
        $this->type = $type;
        $this->save();
        TheaterOfOperationsTimeTape::create('Ocorrência (#'.$this->id.'): Tipo atualizado de '.$old.' para '.$type, $this->theater_of_operations_id, $this->theater_of_operations_sector_id);
        $this->resetBriefTimeTape();
        return $this;
    }

    public function updateStatus($status)
    {
        $old          = $this->status;
        $this->status = $status;
        $this->save();
        TheaterOfOperationsTimeTape::create('Ocorrência (#'.$this->id.'): Status atualizado de '.self::getStatusTextFromNumber($old).' para '.self::getStatusTextFromNumber($status), $this->theater_of_operations_id, $this->theater_of_operations_sector_id);
        $this->resetBriefTimeTape();
        return $this;
    }

    public function updateObservations($observations)
    {
        $old                = $this->observations;
        $this->observations = $observations;
        $this->save();
        return $this;
    }

    public function updateLocation($location)
    {
        $old            = $this->location;
        $this->location = $location;
        $this->save();
        TheaterOfOperationsTimeTape::create('Ocorrência (#'.$this->id.'): Localização atualizada de '.$old.' para '.$location, $this->theater_of_operations_id, $this->theater_of_operations_sector_id);
        $this->resetBriefTimeTape();
        return $this;
    }

    public function updateGPSLocation($lat, $long)
    {
        $old        = $this->lat.' '.$this->long;
        $this->lat  = $lat;
        $this->long = $long;
        $this->save();
        TheaterOfOperationsTimeTape::create('Ocorrência (#'.$this->id.'): Localização GPS atualizada de LatLong '.$old.' para LatLong '.$lat.' '.$long, $this->theater_of_operations_id, $this->theater_of_operations_sector_id);
        $this->resetBriefTimeTape();
        return $this;
    }

    public function theater_of_operations()
    {
        return $this->belongsTo(TheaterOfOperations::class, 'theater_of_operations_id', 'id')->withTrashed();
    }

    public function sector()
    {
        return $this->belongsTo(TheaterOfOperationsSector::class, 'theater_of_operations_sector_id', 'id')->withTrashed();
    }

    public function event_units()
    {
        return $this->hasMany(TheaterOfOperationsEventUnit::class, 'theater_of_operations_event_id', 'id');
    }

    public function victims()
    {
        return $this->hasMany(TheaterOfOperationsEventVictim::class, 'theater_of_operations_event_id', 'id');
    }

    public function active_victims()
    {
        return $this->victims()->whereIn('status', [TheaterOfOperationsEventVictim::STATUS_ON_SCENE,TheaterOfOperationsEventVictim::STATUS_ON_WAY_TO_DESTINATION]);
    }
}
