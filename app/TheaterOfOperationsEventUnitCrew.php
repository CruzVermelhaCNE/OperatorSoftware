<?php
declare(strict_types=1);

namespace App;

use Illuminate\Database\Eloquent\Model;

class TheaterOfOperationsEventUnitCrew extends Model
{
    public static function create($theater_of_operations_event_unit_id, $theater_of_operations_crew_id)
    {
        $theater_of_operations_event_unit                                      = new TheaterOfOperationsEventUnitCrew();
        $theater_of_operations_event_unit->theater_of_operations_event_unit_id = $theater_of_operations_event_unit_id;
        $theater_of_operations_event_unit->theater_of_operations_crew_id       = $theater_of_operations_crew_id;
        $theater_of_operations_event_unit->save();

        return $theater_of_operations_event_unit;
    }

    public function getEventAttribute()
    {
        return $this->event_unit->event;
    }

    public function getUnitAttribute()
    {
        return $this->event_unit->unit;
    }

    public function event_unit()
    {
        return $this->belongsTo(TheaterOfOperationsEventUnit::class, 'theater_of_operations_event_unit_id', 'id');
    }

    public function crew()
    {
        return $this->belongsTo(TheaterOfOperationsCrew::class, 'theater_of_operations_crew_id', 'id');
    }
}
