<?php
declare(strict_types=1);

namespace App;

use Illuminate\Database\Eloquent\Model;

class TheaterOfOperationsUnitGeoTracking extends Model
{
    public static function create($theater_of_operations_unit_id, $system, $observations)
    {
        $theater_of_operations_unit_geotracking                                = new TheaterOfOperationsUnitGeoTracking();
        $theater_of_operations_unit_geotracking->theater_of_operations_unit_id = $theater_of_operations_unit_id;
        $theater_of_operations_unit_geotracking->system                        = $system;
        $theater_of_operations_unit_geotracking->observations                  = $observations;
        $theater_of_operations_unit_geotracking->save();
        return $theater_of_operations_unit_geotracking;
    }

    public function updateExternalID($external_id)
    {
        $this->external_id = $external_id;
        $this->save();
        return $this;
    }

    public function updateObservations($observations)
    {
        $this->observations = $observations;
        $this->save();
        return $this;
    }

    public function remove()
    {
        return $this->delete();
    }

    public function unit()
    {
        return $this->hasOne(TheaterOfOperationsUnit::class, 'theater_of_operations_unit_id', 'id');
    }
}