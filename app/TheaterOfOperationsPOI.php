<?php
declare(strict_types=1);

namespace App;

use Exception;
use Illuminate\Database\Eloquent\Model;

class TheaterOfOperationsPOI extends Model
{
    protected $table = 'theater_of_operations_pois';

    public static function create($name, $location, $lat, $long, $symbol, $observations, $theater_of_operations_id, $theater_of_operations_sector_id)
    {
        if ($theater_of_operations_id == null && $theater_of_operations_sector_id == null) {
            throw new Exception('Major Event ID or Major Event Sector ID must be defined');
        }
        $theater_of_operations_poi                                  = new TheaterOfOperationsPOI();
        $theater_of_operations_poi->name                            = $name;
        $theater_of_operations_poi->location                        = $location;
        $theater_of_operations_poi->lat                             = $lat;
        $theater_of_operations_poi->long                            = $long;
        $theater_of_operations_poi->symbol                          = $symbol;
        $theater_of_operations_poi->observations                    = $observations;
        $theater_of_operations_poi->theater_of_operations_id        = $theater_of_operations_id;
        $theater_of_operations_poi->theater_of_operations_sector_id = $theater_of_operations_sector_id;
        $theater_of_operations_poi->save();
        TheaterOfOperationsTimeTape::create('Ponto de Interesse (#'.$theater_of_operations_poi->id.'): '.$name.' criado', $theater_of_operations_poi->theater_of_operations_id, $theater_of_operations_poi->theater_of_operations_sector_id, TheaterOfOperationsTimeTape::TYPE_CREATION_DELETION);
        return $theater_of_operations_poi;
    }

    public function updateName($name)
    {
        $old        = $this->name;
        $this->name = $name;
        $this->save();
        TheaterOfOperationsTimeTape::create('Ponto de Interesse (#'.$this->id.'): '.$this->name.' - Nome atualizado de '.$old.' para '.$name, $this->theater_of_operations_id, $this->theater_of_operations_sector_id);
        return $this;
    }

    public function updateLocation($location)
    {
        $old            = $this->location;
        $this->location = $location;
        $this->save();
        TheaterOfOperationsTimeTape::create('Ponto de Interesse (#'.$this->id.'): '.$this->name.' - Localização atualizada de '.$old.' para '.$location, $this->theater_of_operations_id, $this->theater_of_operations_sector_id);
        return $this;
    }

    public function updateGPSLocation($lat, $long)
    {
        $old        = $this->lat.' '.$this->long;
        $this->lat  = $lat;
        $this->long = $long;
        $this->save();
        TheaterOfOperationsTimeTape::create('Ponto de Interesse (#'.$this->id.'): '.$this->name.' - Localização GPS atualizada de LatLong '.$old.' para LatLong '.$lat.' '.$long, $this->theater_of_operations_id, $this->theater_of_operations_sector_id);
        return $this;
    }

    public function updateSymbol($symbol)
    {
        $old          = $this->symbol;
        $this->symbol = $symbol;
        $this->save();
        TheaterOfOperationsTimeTape::create('Ponto de Interesse (#'.$this->id.'): '.$this->name.' - Simbolo atualizado de '.$old.' para '.$symbol, $this->theater_of_operations_id, $this->theater_of_operations_sector_id);
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
        foreach ($this->units as $unit) {
            $unit->assignToPOI(null);
        }
        TheaterOfOperationsTimeTape::create('Ponto de Interesse (#'.$this->id.'): '.$this->name.' - '.$this->name.' removido', $this->theater_of_operations_id, $this->theater_of_operations_sector_id, TheaterOfOperationsTimeTape::TYPE_CREATION_DELETION);
        $this->delete();
    }

    public function theater_of_operations()
    {
        return $this->belongsTo(TheaterOfOperations::class, 'theater_of_operations_id', 'id')->withTrashed();
    }

    public function sector()
    {
        return $this->belongsTo(TheaterOfOperationsSector::class, 'theater_of_operations_sector_id', 'id')->withTrashed();
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
