<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class COVID19AmbulanceContact extends Model
{
    protected $table = "covid19_ambulance_contacts";

    public static function createContact($ambulance_id,$contact,$name,$sms) {
        $contact = new COVID19AmbulanceContact();
        $contact->ambulance_id = $ambulance_id;
        $contact->contact = $contact;
        $contact->name = $name;
        $contact->sms = $sms;
        $contact->save();
    }
}
