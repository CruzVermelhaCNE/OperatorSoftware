<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class COVID19AmbulanceTeamMember extends Model
{
    protected $table = 'covid19_ambulance_team_members';

    use SoftDeletes;

    public static function createAmbulanceTeamMember($ambulance_id, $case_id, $name, $age, $contact, $type)
    {
        $ambulance_team               = new COVID19AmbulanceTeamMember();
        $ambulance_team->ambulance_id = $ambulance_id;
        $ambulance_team->case_id      = $case_id;
        $ambulance_team->name         = $name;
        $ambulance_team->age          = $age;
        $ambulance_team->contact      = $contact;
        $ambulance_team->type         = $type;
        $ambulance_team->save();
    }

    public function updateName($name)
    {
        $this->name = $name;
        $this->save();
    }

    public function updateAge($age)
    {
        $this->age = $age;
        $this->save();
    }

    public function updateContact($contact)
    {
        $this->contact = $contact;
        $this->save();
    }

    public function updateType($type)
    {
        $this->type = $type;
        $this->save();
    }
}
