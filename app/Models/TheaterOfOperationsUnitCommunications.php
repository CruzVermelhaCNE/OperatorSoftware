<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TheaterOfOperationsUnitCommunications extends Model
{
    public static function create($theater_of_operations_unit_id, $type, $observations)
    {
        $theater_of_operations_unit_communications                                = new TheaterOfOperationsUnitCommunications();
        $theater_of_operations_unit_communications->theater_of_operations_unit_id = $theater_of_operations_unit_id;
        $theater_of_operations_unit_communications->type                          = $type;
        $theater_of_operations_unit_communications->observations                  = $observations;
        $theater_of_operations_unit_communications->save();
        return $theater_of_operations_unit_communications;
    }

    public function updateType($type)
    {
        $this->type = $type;
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
        return $this->belongsTo(TheaterOfOperationsUnit::class, 'theater_of_operations_unit_id', 'id');
    }
}
