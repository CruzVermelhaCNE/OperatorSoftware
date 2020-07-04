<?php
declare(strict_types=1);

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class TheaterOfOperationsEventUnitTimings extends Model
{
    protected $table = 'theater_of_operations_event_unit_timings';

    public static function create($theater_of_operations_event_unit_id)
    {
        $theater_of_operations_event_unit                                      = new TheaterOfOperationsEventUnitTimings();
        $theater_of_operations_event_unit->theater_of_operations_event_unit_id = $theater_of_operations_event_unit_id;
        $theater_of_operations_event_unit->activation                          = Carbon::now();
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

    public function updateActivation($date)
    {
        $this->activation = $date;
        $this->save();
        return $this;
    }

    public function updateOnWayToScene($date)
    {
        $this->on_way_to_scene = $date;
        $this->save();
        return $this;
    }

    public function updateArrivalOnScene($date)
    {
        $this->arrival_on_scene = $date;
        $this->save();
        return $this;
    }

    public function updateDepartureFromScene($date)
    {
        $this->departure_from_scene = $date;
        $this->save();
        return $this;
    }

    public function updateArrivalOnDestination($date)
    {
        $this->arrival_on_destination = $date;
        $this->save();
        return $this;
    }

    public function updateDepartureFromDestination($date)
    {
        $this->departure_from_destination = $date;
        $this->save();
        return $this;
    }

    public function updateAvailable($date)
    {
        $this->available = $date;
        $this->save();
        return $this;
    }

    public function event_unit()
    {
        return $this->belongsTo(TheaterOfOperationsEventUnit::class, 'theater_of_operations_event_unit_id', 'id');
    }
}
