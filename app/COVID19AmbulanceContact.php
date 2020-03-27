<?php
declare(strict_types=1);

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class COVID19AmbulanceContact extends Model
{
    use Notifiable;
    protected $table = 'covid19_ambulance_contacts';

    public static function createContact($ambulance_id, $contact, $name, $sms)
    {
        $new_contact               = new COVID19AmbulanceContact();
        $new_contact->ambulance_id = $ambulance_id;
        $new_contact->contact      = $contact;
        $new_contact->name         = $name;
        $new_contact->sms          = $sms;
        $new_contact->save();
    }

    public function getFullNumber()
    {
        return '351' . $this->contact;
    }
}

