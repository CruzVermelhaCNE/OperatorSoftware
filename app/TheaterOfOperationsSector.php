<?php
declare(strict_types=1);

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TheaterOfOperationsSector extends Model
{
    use SoftDeletes;

    public static function create($name, $location, $lat, $long, $observations, $theater_of_operations_id)
    {
        $theater_of_operations_sector                           = new TheaterOfOperationsSector();
        $theater_of_operations_sector->name                     = $name;
        $theater_of_operations_sector->location                 = $location;
        $theater_of_operations_sector->lat                      = $lat;
        $theater_of_operations_sector->long                     = $long;
        $theater_of_operations_sector->observations             = $observations;
        $theater_of_operations_sector->theater_of_operations_id = $theater_of_operations_id;
        $theater_of_operations_sector->save();
        TheaterOfOperationsTimeTape::create('Ocorrência Major: Sector '.$name.' criado', $theater_of_operations_id, null, TheaterOfOperationsTimeTape::TYPE_CREATION_DELETION);
        TheaterOfOperationsTimeTape::create('Sector: '.$name.' criado', null, $theater_of_operations_sector->id, TheaterOfOperationsTimeTape::TYPE_CREATION_DELETION);
        return $theater_of_operations_sector;
    }

    public function updateName($name)
    {
        $old        = $this->name;
        $this->name = $name;
        $this->save();
        TheaterOfOperationsTimeTape::create('Sector: Nome atualizado de '.$old.' para '.$name, null, $this->id);
        return $this;
    }

    public function updateLocation($location)
    {
        $old            = $this->location;
        $this->location = $location;
        $this->save();
        TheaterOfOperationsTimeTape::create('Sector: Localização atualizada de '.$old.' para '.$location, null, $this->id);
        return $this;
    }

    public function updateGPSLocation($lat, $long)
    {
        $old        = $this->lat.' '.$this->long;
        $this->lat  = $lat;
        $this->long = $long;
        $this->save();
        TheaterOfOperationsTimeTape::create('Sector: Localização GPS atualizada de LatLong '.$old.' para LatLong '.$lat.' '.$long, null, $this->id);
        return $this;
    }

    public function updateObservations($observations)
    {
        $old                = $this->observations;
        $this->observations = $observations;
        $this->save();
        return $this;
    }

    public function remove()
    {
        TheaterOfOperationsTimeTape::create('Ocorrência Major: Sector '.$this->name.' removido', $this->theater_of_operations_id, null, TheaterOfOperationsTimeTape::TYPE_CREATION_DELETION);
        TheaterOfOperationsTimeTape::create('Sector: '.$this->name.' removido', null, $this->id, TheaterOfOperationsTimeTape::TYPE_CREATION_DELETION);
        $this->delete();
    }

    public function theater_of_operations()
    {
        return $this->belongsTo(TheaterOfOperationsEvent::class, 'theater_of_operations_id', 'id')->withTrashed();
    }

    public function time_tape()
    {
        return $this->hasMany(TheaterOfOperationsTimeTape::class, 'theater_of_operations_sector_id', 'id');
    }

    public function coordination()
    {
        return $this->hasMany(TheaterOfOperationsCoordination::class, 'theater_of_operations_sector_id', 'id');
    }

    public function communication_channels()
    {
        return $this->hasMany(TheaterOfOperationsCommunicationChannel::class, 'theater_of_operations_sector_id', 'id');
    }

    public function pois()
    {
        return $this->hasMany(TheaterOfOperationsPOI::class, 'theater_of_operations_sector_id', 'id');
    }

    public function events()
    {
        return $this->hasMany(TheaterOfOperationsEvent::class, 'theater_of_operations_sector_id', 'id');
    }

    public function units()
    {
        return $this->hasMany(TheaterOfOperationsUnit::class, 'theater_of_operations_sector_id', 'id');
    }

    public function crews()
    {
        return $this->hasMany(TheaterOfOperationsCrew::class, 'theater_of_operations_sector_id', 'id');
    }
}
