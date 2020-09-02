<?php
declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class TheaterOfOperationsUnit extends Model
{
    protected $appends = [
        'status_text',
        'lat',
        'long',
    ];

    protected $dates = [
        'demobilized_at',
    ];

    private const CACHE_BRIEF_TIMETAPE         = 'TheaterOfOperations_Units_Brief_TimeTape_';
    private const CACHE_CREWS                  = 'TheaterOfOperations_Units_Crews_';
    private const CACHE_COMMUNICATION_CHANNELS = 'TheaterOfOperations_Units_Communication_Channels_';
    private const CACHE_GEOTRACKING            = 'TheaterOfOperations_Units_Geotracking_';

    public const STATUS_INOP                  = 0;
    public const STATUS_BASE                  = 1;
    public const STATUS_DISPATCHED            = 2;
    public const STATUS_ON_WAY_TO_SCENE       = 3;
    public const STATUS_ON_SCENE              = 4;
    public const STATUS_ON_WAY_TO_DESTINATION = 5;
    public const STATUS_ON_DESTINATION        = 6;
    public const STATUS_ON_WAY_TO_BASE        = 7;
    public const STATUS_DEMOBILIZED           = 8;

    private static function getStatusTextFromNumber($status)
    {
        switch ($status) {
            case self::STATUS_INOP:
                return 'INOP';
            case self::STATUS_BASE:
                return 'Na Base';
            case self::STATUS_DISPATCHED:
                return 'Despacho';
            case self::STATUS_ON_WAY_TO_SCENE:
                return 'A Caminho do Local';
            case self::STATUS_ON_SCENE:
                return 'No Local';
            case self::STATUS_ON_WAY_TO_DESTINATION:
                return 'A Caminho do Destino';
            case self::STATUS_ON_DESTINATION:
                return 'No Destino';
            case self::STATUS_ON_WAY_TO_BASE:
                return 'A Caminho da Base';
            case self::STATUS_DEMOBILIZED:
                return 'Desmobilizado';
        }
    }

    public static function create($type, $plate, $tail_number, $observations, $structure, $base_lat, $base_long, $theater_of_operations_id)
    {
        $theater_of_operations_unit                           = new TheaterOfOperationsUnit();
        $theater_of_operations_unit->type                     = $type;
        $theater_of_operations_unit->plate                    = $plate;
        $theater_of_operations_unit->tail_number              = $tail_number;
        $theater_of_operations_unit->observations             = $observations;
        $theater_of_operations_unit->structure                = $structure;
        $theater_of_operations_unit->base_lat                 = $base_lat;
        $theater_of_operations_unit->base_long                = $base_long;
        $theater_of_operations_unit->theater_of_operations_id = $theater_of_operations_id;
        $theater_of_operations_unit->save();
        TheaterOfOperationsTimeTape::create('Meio (#'.$theater_of_operations_unit->id.'): '.($theater_of_operations_unit->tail_number?$theater_of_operations_unit->tail_number:$theater_of_operations_unit->plate).' - Atribuida à Teatro de Operações', $theater_of_operations_unit->theater_of_operations_id, $theater_of_operations_unit->major_sector_id, TheaterOfOperationsTimeTape::TYPE_UNIT_MOVEMENTS);
        return $theater_of_operations_unit;
    }

    public function recreate()
    {
        $this->status         = self::STATUS_INOP;
        $this->demobilized_at = null;
        $this->save();
        TheaterOfOperationsTimeTape::create('Meio (#'.$this->id.'): '.($this->tail_number?$this->tail_number:$this->plate).' - Remobilizado', $this->theater_of_operations_id, $this->major_sector_id, TheaterOfOperationsTimeTape::TYPE_UNIT_MOVEMENTS);
        $this->resetBriefTimeTape();
        $this->theater_of_operations->resetUnitsListing();
    }

    public function getDeploymentAttribute()
    {
        if ($this->status == self::STATUS_DEMOBILIZED) {
            return 'Desmobilizado';
        }
        if ($this->active_event) {
            return 'Ocorrência #'.$this->active_event->id;
        }
        if ($this->poi) {
            return $this->poi->name;
        }
        return 'Sem Destacamento';
    }

    public function getLatAttribute()
    {
        if ($this->geotracking->count() != 0) {
            $lat = $this->geotracking->first()->lat;
            if ($lat) {
                return $lat;
            }
        }
        if ($this->active_event) {
            return $this->active_event->lat;
        }
        if ($this->poi) {
            return $this->poi->lat;
        }
        return $this->theater_of_operations->lat;
    }

    public function getLongAttribute()
    {
        if ($this->geotracking->count() != 0) {
            $long = $this->geotracking->first()->long;
            if ($long) {
                return $long;
            }
        }
        if ($this->active_event) {
            return $this->active_event->long;
        }
        if ($this->poi) {
            return $this->poi->long;
        }
        return $this->theater_of_operations->long;
    }

    public function getStatusTextAttribute()
    {
        return self::getStatusTextFromNumber($this->status);
    }

    public function getActiveEventAttribute()
    {
        $active_events   = $this->events->whereIn('status', [TheaterOfOperationsEvent::STATUS_DISPATCH,TheaterOfOperationsEvent::STATUS_ON_GOING,TheaterOfOperationsEvent::STATUS_IN_CONCLUSION]);
        $event_to_return = null;
        foreach ($active_events as $active_event) {
            $active_victims = $active_event->active_victims()->where('theater_of_operations_event_unit_id', '!=', null)->get();
            if ($active_victims) {
                foreach ($active_victims as $active_victim) {
                    if ($active_victim->unit) {
                        if ($active_victim->unit->id == $this->id) {
                            $event_to_return = $active_victim->event;
                        }
                    }
                }
            }
        }
        if ($event_to_return == null) {
            $event_to_return = $active_events->first();
        }
        return $event_to_return;
    }

    public function isDemobilized()
    {
        return $this->status == self::STATUS_DEMOBILIZED;
    }

    public function generateBriefTimeTape()
    {
        $timetape = $this->theater_of_operations->time_tape()->where('description', 'LIKE', 'Meio (#'.$this->id.'):%')->orderby('id', 'DESC')->limit(10)->get();
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

    public function generateCrews()
    {
        $crews = $this->crews;
        $array = [];
        foreach ($crews as $crew) {
            $array[] = [
                $crew->name,
                $crew->contact,
                $crew->age,
                $crew->course,
                $crew->observations,
                $crew->lat,
                $crew->long,
                $crew->theater_of_operations_poi_id,
                $crew->theater_of_operations_unit_id,
                $crew->id,
            ];
        }
        $data = \json_encode($array);
        Cache::put(self::CACHE_CREWS.$this->id, $data);
        return $data;
    }

    public function resetCrews()
    {
        if (Cache::has(self::CACHE_CREWS.$this->id)) {
            Cache::pull(self::CACHE_CREWS.$this->id);
        }
    }

    public function getCrews()
    {
        $data = '';
        if (Cache::has(self::CACHE_CREWS.$this->id)) {
            $data = Cache::get(self::CACHE_CREWS.$this->id);
        } else {
            $data = $this->generateCrews();
        }
        return $data;
    }

    public function generateComunicationChannels()
    {
        $communications = $this->communications;
        $array          = [];
        foreach ($communications as $communication) {
            $array[] = [
                $communication->type,
                $communication->observations,
                $communication->id,
            ];
        }
        $data = \json_encode($array);
        Cache::put(self::CACHE_COMMUNICATION_CHANNELS.$this->id, $data);
        return $data;
    }

    public function resetCommunicationChannels()
    {
        if (Cache::has(self::CACHE_COMMUNICATION_CHANNELS.$this->id)) {
            Cache::pull(self::CACHE_COMMUNICATION_CHANNELS.$this->id);
        }
    }

    public function getCommunicationChannels()
    {
        $data = '';
        if (Cache::has(self::CACHE_COMMUNICATION_CHANNELS.$this->id)) {
            $data = Cache::get(self::CACHE_COMMUNICATION_CHANNELS.$this->id);
        } else {
            $data = $this->generateComunicationChannels();
        }
        return $data;
    }

    public function generateGeotracking()
    {
        $geotracking = $this->geotracking;
        $array       = [];
        foreach ($geotracking as $single_geotracking) {
            $array[] = [
                $single_geotracking->system,
                $single_geotracking->external_id,
                $single_geotracking->id,
            ];
        }
        $data = \json_encode($array);
        Cache::put(self::CACHE_GEOTRACKING.$this->id, $data);
        return $data;
    }

    public function resetGeotracking()
    {
        if (Cache::has(self::CACHE_GEOTRACKING.$this->id)) {
            Cache::pull(self::CACHE_GEOTRACKING.$this->id);
        }
    }

    public function getGeotracking()
    {
        $data = '';
        if (Cache::has(self::CACHE_GEOTRACKING.$this->id)) {
            $data = Cache::get(self::CACHE_GEOTRACKING.$this->id);
        } else {
            $data = $this->generateGeotracking();
        }
        return $data;
    }

    public function getEventsAttribute()
    {
        $events      = collect();
        $event_units = $this->event_units;
        foreach ($event_units as $event_unit) {
            $events->push($event_unit->event);
        }
        return $events;
    }

    public function assignToSector($theater_of_operations_sector_id)
    {
        if ($this->theater_of_operations_poi_id) {
            TheaterOfOperationsTimeTape::create('Meio (#'.$this->id.'): '.($this->tail_number?$this->tail_number:$this->plate).' - Removido do Ponto de Interesse '.$this->poi->name, $this->theater_of_operations_id, $this->theater_of_operations_sector_id, TheaterOfOperationsTimeTape::TYPE_UNIT_MOVEMENTS);
            $this->theater_of_operations_poi_id = null;
        }
        if ($this->theater_of_operations_sector_id) {
            TheaterOfOperationsTimeTape::create('Meio (#'.$this->id.'): '.($this->tail_number?$this->tail_number:$this->plate).' - Removido do Sector', null, $this->theater_of_operations_sector_id, TheaterOfOperationsTimeTape::TYPE_UNIT_MOVEMENTS);
            $this->theater_of_operations_sector_id = null;
        }
        $this->theater_of_operations_sector_id = $theater_of_operations_sector_id;
        $this->save();
        $this->load('poi');
        $this->load('theater_of_operations');
        if ($this->theater_of_operations_sector_id) {
            TheaterOfOperationsTimeTape::create('Meio (#'.$this->id.'): '.($this->tail_number?$this->tail_number:$this->plate).' - Atribuido ao Sector '.$this->sector->name, $this->sector->theater_of_operations->id, null, TheaterOfOperationsTimeTape::TYPE_UNIT_MOVEMENTS);
            TheaterOfOperationsTimeTape::create('Meio (#'.$this->id.'): '.($this->tail_number?$this->tail_number:$this->plate).' - Atribuido ao Sector', null, $theater_of_operations_sector_id, TheaterOfOperationsTimeTape::TYPE_UNIT_MOVEMENTS);
        }
        $this->resetBriefTimeTape();
        return $this;
    }

    public function assignToPOI($theater_of_operations_poi_id)
    {
        if ($this->theater_of_operations_poi_id) {
            TheaterOfOperationsTimeTape::create('Meio (#'.$this->id.'): '.($this->tail_number?$this->tail_number:$this->plate).' - Removido do Ponto de Interesse '.$this->poi->name, $this->theater_of_operations_id, $this->theater_of_operations_sector_id, TheaterOfOperationsTimeTape::TYPE_UNIT_MOVEMENTS);
            $this->theater_of_operations_poi_id = null;
        }
        $this->theater_of_operations_poi_id = $theater_of_operations_poi_id;
        $this->save();
        $this->load('poi');
        $this->load('theater_of_operations');
        if ($this->theater_of_operations_poi_id) {
            TheaterOfOperationsTimeTape::create('Meio (#'.$this->id.'): '.($this->tail_number?$this->tail_number:$this->plate).' - Atribuido ao Ponto de Interesse '.$this->poi->name, $this->theater_of_operations_id, $this->theater_of_operations_sector_id, TheaterOfOperationsTimeTape::TYPE_UNIT_MOVEMENTS);
        }
        $this->resetBriefTimeTape();
        $this->theater_of_operations->resetUnitsListing();
        return $this;
    }

    public function updateType($type)
    {
        $old        = $this->type;
        $this->type = $type;
        $this->save();
        TheaterOfOperationsTimeTape::create('Meio (#'.$this->id.'): '.($this->tail_number?$this->tail_number:$this->plate).' - Tipo atualizado de '.$old.' para '.$type, $this->theater_of_operations_id, $this->major_sector_id);
        $this->resetBriefTimeTape();
        $this->theater_of_operations->resetUnitsListing();
        return $this;
    }

    public function updatePlate($plate)
    {
        $old = $this->plate;
        if ($old == null) {
            $old = 'N/A';
        }
        $this->plate = $plate;
        $this->save();
        TheaterOfOperationsTimeTape::create('Meio (#'.$this->id.'): '.($this->tail_number?$this->tail_number:$this->plate).' - Matricula atualizada de '.$old.' para '.$plate, $this->theater_of_operations_id, $this->major_sector_id);
        $this->resetBriefTimeTape();
        $this->theater_of_operations->resetUnitsListing();
        return $this;
    }

    public function updateTailNumber($tail_number)
    {
        $old = $this->tail_number;
        if ($old == null) {
            $old = 'N/A';
        }
        $this->tail_number = $tail_number;
        $this->save();
        TheaterOfOperationsTimeTape::create('Meio (#'.$this->id.'): '.($this->tail_number?$this->tail_number:$this->plate).' - Nº de Cauda atualizada de '.$old.' para '.$tail_number, $this->theater_of_operations_id, $this->major_sector_id);
        $this->resetBriefTimeTape();
        $this->theater_of_operations->resetUnitsListing();
        return $this;
    }

    public function updateStatus($status)
    {
        $old          = $this->status;
        $this->status = $status;
        $this->save();
        TheaterOfOperationsTimeTape::create('Meio (#'.$this->id.'): '.($this->tail_number?$this->tail_number:$this->plate).' - Status atualizado de '.self::getStatusTextFromNumber($old).' para '.self::getStatusTextFromNumber($status), $this->theater_of_operations_id, $this->major_sector_id);
        $this->resetBriefTimeTape();
        $this->theater_of_operations->resetUnitsListing();
        return $this;
    }

    public function updateObservations($observations)
    {
        $old                = $this->observations;
        $this->observations = $observations;
        $this->save();
        return $this;
    }

    public function updateStructure($structure)
    {
        $old             = $this->structure;
        $this->structure = $structure;
        $this->save();
        TheaterOfOperationsTimeTape::create('Meio (#'.$this->id.'): '.($this->tail_number?$this->tail_number:$this->plate).' - Estrutura atualizada de '.$old.' para '.$structure, $this->theater_of_operations_id, $this->major_sector_id);
        $this->resetBriefTimeTape();
        $this->theater_of_operations->resetUnitsListing();
        return $this;
    }

    public function updateBaseGPSLocation($base_lat, $base_long)
    {
        $old             = $this->base_lat.' '.$this->base_long;
        $this->base_lat  = $base_lat;
        $this->base_long = $base_long;
        $this->save();
        TheaterOfOperationsTimeTape::create('Meio (#'.$this->id.'): '.($this->tail_number?$this->tail_number:$this->plate).' - Localização GPS da base atualizada de LatLong '.$old.' para '.$base_lat.' '.$base_long, $this->theater_of_operations_id, $this->major_sector_id);
        $this->resetBriefTimeTape();
        return $this;
    }

    public function demobilize()
    {
        foreach ($this->crews as $crew) {
            $crew->demobilize();
        }
        TheaterOfOperationsTimeTape::create('Meio (#'.$this->id.'): '.($this->tail_number?$this->tail_number:$this->plate).' - Desmobilizado', $this->theater_of_operations_id, $this->major_sector_id);
        $this->resetBriefTimeTape();
        $this->status         = self::STATUS_DEMOBILIZED;
        $this->demobilized_at = Carbon::now();
        $this->save();
        $this->theater_of_operations->resetUnitsListing();
    }

    public function theater_of_operations()
    {
        return $this->belongsTo(TheaterOfOperations::class, 'theater_of_operations_id', 'id')->withTrashed();
    }

    public function sector()
    {
        return $this->belongsTo(TheaterOfOperationsSector::class, 'theater_of_operations_sector_id', 'id')->withTrashed();
    }

    public function poi()
    {
        return $this->belongsTo(TheaterOfOperationsPOI::class, 'theater_of_operations_poi_id', 'id');
    }

    public function crews()
    {
        return $this->hasMany(TheaterOfOperationsCrew::class, 'theater_of_operations_unit_id', 'id');
    }

    public function event_units()
    {
        return $this->hasMany(TheaterOfOperationsEventUnit::class, 'theater_of_operations_unit_id', 'id');
    }

    public function communications()
    {
        return $this->hasMany(TheaterOfOperationsUnitCommunications::class, 'theater_of_operations_unit_id', 'id');
    }

    public function geotracking()
    {
        return $this->hasMany(TheaterOfOperationsUnitGeoTracking::class, 'theater_of_operations_unit_id', 'id');
    }
}
