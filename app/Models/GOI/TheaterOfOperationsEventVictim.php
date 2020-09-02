<?php
declare(strict_types=1);

namespace App\Models\GOI;

use Illuminate\Database\Eloquent\Model;

class TheaterOfOperationsEventVictim extends Model
{
    public const STATUS_CANCELED              = 0;
    public const STATUS_ON_SCENE              = 1;
    public const STATUS_ASSISTED_ON_SCENE     = 2;
    public const STATUS_ABANDONED_SCENE       = 3;
    public const STATUS_REFUSED_ASSISTANCE    = 4;
    public const STATUS_ON_WAY_TO_DESTINATION = 5;
    public const STATUS_ON_DESTINATION        = 6;

    public const GENDER_MALE   = false;
    public const GENDER_FEMALE = true;

    protected $appends = [
        'status_text',
        'unit',
    ];

    private static function getStatusTextFromNumber($status)
    {
        switch ($status) {
            case self::STATUS_CANCELED:
                return 'Anulada';
            case self::STATUS_ON_SCENE:
                return 'No Local';
            case self::STATUS_ASSISTED_ON_SCENE:
                return 'Assistida no Local';
            case self::STATUS_ABANDONED_SCENE:
                return 'Abandonou o Local';
            case self::STATUS_REFUSED_ASSISTANCE:
                return 'Recusou Assistência';
            case self::STATUS_ON_WAY_TO_DESTINATION:
                return 'A Caminho do Destino';
            case self::STATUS_ON_DESTINATION:
                return 'No Destino';
        }
    }

    public static function create($theater_of_operations_event_id)
    {
        $theater_of_operations_event_victim                                 = new TheaterOfOperationsEventVictim();
        $theater_of_operations_event_victim->theater_of_operations_event_id = $theater_of_operations_event_id;
        $theater_of_operations_event_victim->status                         = self::STATUS_ON_SCENE;
        $theater_of_operations_event_victim->save();
        return $theater_of_operations_event_victim;
    }

    public function getLatAttribute()
    {
        switch ($this->status) {
            case self::STATUS_CANCELED:
            case self::STATUS_ABANDONED_SCENE:
            case self::STATUS_REFUSED_ASSISTANCE:
            case self::STATUS_ASSISTED_ON_SCENE:
            case self::STATUS_ON_SCENE:
                return $this->event->lat;
            case self::STATUS_ON_WAY_TO_DESTINATION:
                return $this->event_unit->unit->lat;
            case self::STATUS_ON_DESTINATION:
                return $this->destination_lat;
        }
    }

    public function getLongAttribute()
    {
        switch ($this->status) {
            case self::STATUS_CANCELED:
            case self::STATUS_ABANDONED_SCENE:
            case self::STATUS_REFUSED_ASSISTANCE:
            case self::STATUS_ASSISTED_ON_SCENE:
            case self::STATUS_ON_SCENE:
                return $this->event->long;
            case self::STATUS_ON_WAY_TO_DESTINATION:
                return $this->event_unit->unit->long;
            case self::STATUS_ON_DESTINATION:
                return $this->destination_long;
        }
    }

    public function getStatusTextAttribute()
    {
        return self::getStatusTextFromNumber($this->status);
    }

    public function getUnitAttribute()
    {
        if ($this->event_unit) {
            return $this->event_unit->unit;
        }
        return null;
    }

    public function updateEventUnit($theater_of_operations_event_unit_id)
    {
        $this->theater_of_operations_event_unit_id = $theater_of_operations_event_unit_id;
        $this->save();
        return $this;
    }

    public function updateName($name)
    {
        $this->name = $name;
        $this->save();
        return $this;
    }

    public function updateAge($age)
    {
        $this->age = $age;
        $this->save();
        return $this;
    }

    public function updateGender($gender)
    {
        $this->gender = $gender;
        $this->save();
        return $this;
    }

    public function updateSNS($sns)
    {
        $this->sns = $sns;
        $this->save();
        return $this;
    }

    public function updateStatus($status)
    {
        $this->status = $status;
        $this->save();
        return $this;
    }

    public function updateCanceledAt($date)
    {
        $this->canceled_at = $date;
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

    public function updateAssistedOnScene($date)
    {
        $this->assisted_on_scene = $date;
        $this->save();
        return $this;
    }

    public function updateRefusedAssistance($date)
    {
        $this->refused_assistance = $date;
        $this->save();
        return $this;
    }

    public function updateAbandonedScene($date)
    {
        $this->abandoned_scene = $date;
        $this->save();
        return $this;
    }

    public function updateObservations($observations)
    {
        $this->observations = $observations;
        $this->save();
        return $this;
    }

    public function updateDestination($destination)
    {
        $this->destination = $destination;
        $this->save();
        return $this;
    }

    public function updateGPSDestination($lat, $long)
    {
        $this->destination_lat  = $lat;
        $this->destination_long = $long;
        $this->save();
        return $this;
    }

    public function event()
    {
        return $this->belongsTo(TheaterOfOperationsEvent::class, 'theater_of_operations_event_id', 'id');
    }

    public function event_unit()
    {
        return $this->hasOne(TheaterOfOperationsEventUnit::class, 'theater_of_operations_event_id', 'theater_of_operations_event_id');
    }
}
