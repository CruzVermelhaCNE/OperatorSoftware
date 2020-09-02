<?php
declare(strict_types=1);

namespace App\Models\GOI;

use Illuminate\Database\Eloquent\Model;

class TheaterOfOperationsEventUnit extends Model
{
    protected $appends = ['crews_listing', 'victims_listing'];

    protected $hidden = ['crews','victims'];

    public static function create($theater_of_operations_event_id, $theater_of_operations_unit_id)
    {
        $theater_of_operations_event_unit                                 = new TheaterOfOperationsEventUnit();
        $theater_of_operations_event_unit->theater_of_operations_event_id = $theater_of_operations_event_id;
        $theater_of_operations_event_unit->theater_of_operations_unit_id  = $theater_of_operations_unit_id;
        $theater_of_operations_event_unit->save();
        TheaterOfOperationsEventUnitTimings::create($theater_of_operations_event_unit->id);
        foreach ($theater_of_operations_event_unit->unit->crews as $crew) {
            TheaterOfOperationsEventUnitCrew::create($theater_of_operations_event_unit->id, $crew->id);
        }
        return $theater_of_operations_event_unit;
    }

    public function getCrewsListingAttribute()
    {
        $crews = $this->crews;
        $array = [];
        foreach ($crews as $crew) {
            $array[] = [
                $crew->crew->name,
                $crew->crew->contact,
                $crew->crew->age,
                $crew->crew->course,
            ];
        }
        return $array;
    }

    public function getVictimsListingAttribute()
    {
        $victims = $this->victims;
        $array   = [];
        foreach ($victims as $victim) {
            $array[] = [
                $victim->name,
                $victim->age,
                $victim->destination,
                $victim->id,
            ];
        }
        return $array;
    }

    public function unit()
    {
        return $this->belongsTo(TheaterOfOperationsUnit::class, 'theater_of_operations_unit_id', 'id');
    }

    public function event()
    {
        return $this->belongsTo(TheaterOfOperationsEvent::class, 'theater_of_operations_event_id', 'id');
    }

    public function victims()
    {
        return $this->hasMany(TheaterOfOperationsEventVictim::class, 'theater_of_operations_event_unit_id', 'id');
    }

    public function crews()
    {
        return $this->hasMany(TheaterOfOperationsEventUnitCrew::class, 'theater_of_operations_event_unit_id', 'id');
    }

    public function timings()
    {
        return $this->hasOne(TheaterOfOperationsEventUnitTimings::class, 'theater_of_operations_event_unit_id', 'id');
    }
}
