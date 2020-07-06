<?php
declare(strict_types=1);

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class TheaterOfOperations extends Model
{
    use SoftDeletes;

    private const CACHE_ACTIVE                 = 'TheaterOfOperations_Active';
    private const CACHE_CONCLUDED              = 'TheaterOfOperations_Concluded';
    private const CACHE_BRIEF_TIMETAPE         = 'TheaterOfOperations_Brief_TimeTape_';
    private const CACHE_COORDINATION           = 'TheaterOfOperations_Coordination_';
    private const CACHE_POIS                   = 'TheaterOfOperations_POIs_';
    private const CACHE_COMMUNICATION_CHANNELS = 'TheaterOfOperations_Communication_Channels_';
    private const CACHE_CREWS                  = 'TheaterOfOperations_Crews_';
    private const CACHE_EVENTS                 = 'TheaterOfOperations_Events_';
    private const CACHE_UNITS                  = 'TheaterOfOperations_Units_';

    public static function create($name, $type, $creation_channel, $location, $lat, $long, $level, $observations, $cdos = null)
    {
        $theater_of_operations                   = new TheaterOfOperations();
        $theater_of_operations->name             = $name;
        $theater_of_operations->type             = $type;
        $theater_of_operations->creation_channel = $creation_channel;
        $theater_of_operations->location         = $location;
        $theater_of_operations->lat              = $lat;
        $theater_of_operations->long             = $long;
        $theater_of_operations->level            = $level;
        $theater_of_operations->observations     = $observations;
        $theater_of_operations->cdos             = $cdos;
        $theater_of_operations->save();
        TheaterOfOperationsTimeTape::create('Ocorrência Major: '.$name.' ('.$type.') criada pelo canal '.$creation_channel, $theater_of_operations->id, null, TheaterOfOperationsTimeTape::TYPE_CREATION_DELETION);
        Cache::pull(self::CACHE_ACTIVE);
        return $theater_of_operations;
    }

    public static function generateActive()
    {
        $theaters_of_operations = TheaterOfOperations::all();
        $array                  = [];
        foreach ($theaters_of_operations as $theater_of_operations) {
            $array[] = [
                $theater_of_operations->name,
                $theater_of_operations->type,
                $theater_of_operations->level,
                $theater_of_operations->getEvents()->count(),
                $theater_of_operations->getUnits()->count(),
                $theater_of_operations->getCrews()->count(),
                $theater_of_operations->id,
            ];
        }
        $data = \json_encode($array);
        Cache::put(self::CACHE_ACTIVE, $data);
        return $data;
    }

    public static function resetActive()
    {
        if (Cache::has(self::CACHE_ACTIVE)) {
            Cache::pull(self::CACHE_ACTIVE);
        }
    }

    public static function getActive()
    {
        $data = '';
        if (Cache::has(self::CACHE_ACTIVE)) {
            $data = Cache::get(self::CACHE_ACTIVE);
        } else {
            $data = self::generateActive();
        }
        return $data;
    }

    public static function generateConcluded()
    {
        $theaters_of_operations = TheaterOfOperations::onlyTrashed()->get();
        $array                  = [];
        foreach ($theaters_of_operations as $theater_of_operations) {
            $array[] = [
                $theater_of_operations->name,
                $theater_of_operations->type,
                $theater_of_operations->level,
                $theater_of_operations->getEvents()->count(),
                $theater_of_operations->getUnits()->count(),
                $theater_of_operations->getCrews()->count(),
                $theater_of_operations->id,
            ];
        }
        $data = \json_encode($array);
        Cache::put(self::CACHE_CONCLUDED, $data);
        return $data;
    }

    public static function resetConcluded()
    {
        if (Cache::has(self::CACHE_CONCLUDED)) {
            Cache::pull(self::CACHE_CONCLUDED);
        }
    }

    public static function getConcluded()
    {
        $data = '';
        if (Cache::has(self::CACHE_CONCLUDED)) {
            $data = Cache::get(self::CACHE_CONCLUDED);
        } else {
            $data = self::generateConcluded();
        }
        return $data;
    }

    public function getUnits()
    {
        $units = null;
        if ($this->trashed()) {
            $units = $this->units;
        } else {
            $units = $this->units()->where('status', '!=', TheaterOfOperationsUnit::STATUS_DEMOBILIZED)->get();
        }
        foreach ($this->sectors as $sector) {
            $units = $units->concat($sector->units);
        }
        return $units;
    }

    public function getCrews()
    {
        if ($this->trashed()) {
            return $this->crews()->withTrashed()->get();
        } else {
            return $this->crews;
        }
    }

    public function getEvents()
    {
        $events = $this->events;
        foreach ($this->sectors as $sector) {
            $events = $events->concat($sector->events);
        }
        return $events;
    }

    public function generateBriefTimeTape()
    {
        $timetape = $this->time_tape()->whereIn('type', [TheaterOfOperationsTimeTape::TYPE_CREATION_DELETION,TheaterOfOperationsTimeTape::TYPE_CUSTOM])->orderby('id', 'DESC')->limit(10)->get();
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

    public function generateCoordination()
    {
        $coordination = $this->coordination;
        $array        = [];
        foreach ($coordination as $coordination_entry) {
            $array[] = [
                $coordination_entry->name,
                $coordination_entry->role,
                $coordination_entry->contact,
                $coordination_entry->observations,
                $coordination_entry->id,
            ];
        }
        $data = \json_encode($array);
        Cache::put(self::CACHE_COORDINATION.$this->id, $data);
        return $data;
    }

    public function resetCoordination()
    {
        if (Cache::has(self::CACHE_COORDINATION.$this->id)) {
            Cache::pull(self::CACHE_COORDINATION.$this->id);
        }
    }

    public function getCoordination()
    {
        $data = '';
        if (Cache::has(self::CACHE_COORDINATION.$this->id)) {
            $data = Cache::get(self::CACHE_COORDINATION.$this->id);
        } else {
            $data = $this->generateCoordination();
        }
        return $data;
    }

    public function generatePOIs()
    {
        $pois  = $this->pois;
        $array = [];
        foreach ($pois as $poi_entry) {
            $array[] = [
                $poi_entry->name,
                $poi_entry->location,
                $poi_entry->observations,
                $poi_entry->symbol,
                $poi_entry->lat,
                $poi_entry->long,
                $poi_entry->id,
            ];
        }
        $data = \json_encode($array);
        Cache::put(self::CACHE_POIS.$this->id, $data);
        return $data;
    }

    public function resetPOIs()
    {
        if (Cache::has(self::CACHE_POIS.$this->id)) {
            Cache::pull(self::CACHE_POIS.$this->id);
        }
    }

    public function getPOIs()
    {
        $data = '';
        if (Cache::has(self::CACHE_POIS.$this->id)) {
            $data = Cache::get(self::CACHE_POIS.$this->id);
        } else {
            $data = $this->generatePOIs();
        }
        return $data;
    }

    public function generateEventsListing()
    {
        $events = $this->getEvents();
        $array  = [];
        foreach ($events as $event) {
            $array[] = [
                $event->type,
                $event->location,
                $event->codu?$event->codu:'N/A',
                $event->cdos?$event->cdos:'N/A',
                $event->status_text,
                $event->lat,
                $event->long,
                $event->id,
            ];
        }
        $data = \json_encode($array);
        Cache::put(self::CACHE_EVENTS.$this->id, $data);
        return $data;
    }

    public function resetEventsListing()
    {
        if (Cache::has(self::CACHE_EVENTS.$this->id)) {
            Cache::pull(self::CACHE_EVENTS.$this->id);
        }
    }

    public function getEventsListing()
    {
        $data = '';
        if (Cache::has(self::CACHE_EVENTS.$this->id)) {
            $data = Cache::get(self::CACHE_EVENTS.$this->id);
        } else {
            $data = $this->generateEventsListing();
        }
        return $data;
    }

    public function generateUnitsListing()
    {
        $units = $this->getUnits();
        $array = [];
        foreach ($units as $unit) {
            $array[] = [
                $unit->type,
                $unit->tail_number?$unit->tail_number:'N/A',
                $unit->plate?$unit->plate:'N/A',
                $unit->structure,
                $unit->status_text,
                $unit->deployment,
                $unit->lat,
                $unit->long,
                $unit->id,
            ];
        }
        $data = \json_encode($array);
        Cache::put(self::CACHE_UNITS.$this->id, $data);
        return $data;
    }

    public function resetUnitsListing()
    {
        if (Cache::has(self::CACHE_UNITS.$this->id)) {
            Cache::pull(self::CACHE_UNITS.$this->id);
        }
    }

    public function getUnitsListing()
    {
        $data = '';
        if (Cache::has(self::CACHE_UNITS.$this->id)) {
            $data = Cache::get(self::CACHE_UNITS.$this->id);
        } else {
            $data = $this->generateUnitsListing();
        }
        return $data;
    }

    public function generateCrewsListing()
    {
        $crews = $this->getCrews();
        $array = [];
        foreach ($crews as $crew) {
            $array[] = [
                $crew->name,
                $crew->contact,
                $crew->age,
                $crew->course,
                $crew->observations,
                $crew->deployment,
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

    public function resetCrewsListing()
    {
        if (Cache::has(self::CACHE_CREWS.$this->id)) {
            Cache::pull(self::CACHE_CREWS.$this->id);
        }
    }

    public function getCrewsListing()
    {
        $data = '';
        if (Cache::has(self::CACHE_CREWS.$this->id)) {
            $data = Cache::get(self::CACHE_CREWS.$this->id);
        } else {
            $data = $this->generateCrewsListing();
        }
        return $data;
    }

    public function generateCommunicationChannels()
    {
        $communication_channels = $this->communication_channels;
        $array                  = [];
        foreach ($communication_channels as $communication_channel) {
            $array[] = [
                $communication_channel->type,
                $communication_channel->channel,
                $communication_channel->observations,
                $communication_channel->id,
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
            $data = $this->generateCommunicationChannels();
        }
        return $data;
    }

    public function updateName($name)
    {
        $old        = $this->name;
        $this->name = $name;
        $this->save();
        TheaterOfOperationsTimeTape::create('Ocorrência Major: Nome atualizado de '.$old.' para '.$name, $this->id, null);
        return $this;
    }

    public function updateType($type)
    {
        $old        = $this->type;
        $this->type = $type;
        $this->save();
        TheaterOfOperationsTimeTape::create('Ocorrência Major: Tipo atualizado de '.$old.' para '.$type, $this->id, null);
        return $this;
    }

    public function updateCreationChannel($creation_channel)
    {
        $old                    = $this->creation_channel;
        $this->creation_channel = $creation_channel;
        $this->save();
        TheaterOfOperationsTimeTape::create('Ocorrência Major: Canal de criação atualizado de '.$old.' para '.$creation_channel, $this->id, null);
        return $this;
    }

    public function updateLocation($location)
    {
        $old            = $this->location;
        $this->location = $location;
        $this->save();
        TheaterOfOperationsTimeTape::create('Ocorrência Major: Localização atualizada de '.$old.' para '.$location, $this->id, null);
        return $this;
    }

    public function updateGPSLocation($lat, $long)
    {
        $old        = $this->lat.' '.$this->long;
        $this->lat  = $lat;
        $this->long = $long;
        $this->save();
        TheaterOfOperationsTimeTape::create('Ocorrência Major: Localização GPS atualizada de LatLong '.$old.' para LatLong '.$lat.' '.$long, $this->id, null);
        return $this;
    }

    public function updateLevel($level)
    {
        $old         = $this->level;
        $this->level = $level;
        $this->save();
        TheaterOfOperationsTimeTape::create('Ocorrência Major: Nível atualizado de '.$old.' para '.$level, $this->id, null);
        return $this;
    }

    public function updateObservations($observations)
    {
        $old                = $this->observations;
        $this->observations = $observations;
        $this->save();
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
        TheaterOfOperationsTimeTape::create('Ocorrência Major: Número CDOS atualizado de '.$old.' para '.$cdos, $this->id, null);
        return $this;
    }

    public function remove()
    {
        TheaterOfOperationsTimeTape::create('Ocorrência Major: '.$this->name.' ('.$this->type.') encerrado', $this->id, null, TheaterOfOperationsTimeTape::TYPE_CREATION_DELETION);
        $this->delete();
        $this->resetCrewsListing();
        $this->resetUnitsListing();
    }

    public function reopen()
    {
        TheaterOfOperationsTimeTape::create('Ocorrência Major: '.$this->name.' ('.$this->type.') reaberto', $this->id, null, TheaterOfOperationsTimeTape::TYPE_CREATION_DELETION);
        $this->restore();
        $this->resetCrewsListing();
        $this->resetUnitsListing();
    }

    public function time_tape()
    {
        return $this->hasMany(TheaterOfOperationsTimeTape::class, 'theater_of_operations_id', 'id');
    }

    public function coordination()
    {
        return $this->hasMany(TheaterOfOperationsCoordination::class, 'theater_of_operations_id', 'id');
    }

    public function communication_channels()
    {
        return $this->hasMany(TheaterOfOperationsCommunicationChannel::class, 'theater_of_operations_id', 'id');
    }

    public function sectors()
    {
        return $this->hasMany(TheaterOfOperationsSector::class, 'theater_of_operations_id', 'id');
    }

    public function pois()
    {
        return $this->hasMany(TheaterOfOperationsPOI::class, 'theater_of_operations_id', 'id');
    }

    public function events()
    {
        return $this->hasMany(TheaterOfOperationsEvent::class, 'theater_of_operations_id', 'id');
    }

    public function units()
    {
        return $this->hasMany(TheaterOfOperationsUnit::class, 'theater_of_operations_id', 'id');
    }

    public function crews()
    {
        return $this->hasMany(TheaterOfOperationsCrew::class, 'theater_of_operations_id', 'id');
    }
}
