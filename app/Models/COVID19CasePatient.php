<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class COVID19CasePatient extends Model
{
    protected $table = 'covid19_case_patients';

    public static function createCasePatient($case_id, $RNU, $firstname, $lastname, $sex, $DoB, $suspect, $suspect_validation, $confirmed, $invasive_care)
    {
        $case_patient                     = new COVID19CasePatient();
        $case_patient->case_id            = $case_id;
        $case_patient->RNU                = $RNU;
        $case_patient->firstname          = $firstname;
        $case_patient->lastname           = $lastname;
        $case_patient->sex                = $sex;
        $case_patient->DoB                = $DoB;
        $case_patient->suspect            = $suspect;
        $case_patient->suspect_validation = $suspect_validation;
        $case_patient->confirmed          = $confirmed;
        $case_patient->invasive_care      = $invasive_care;
        $case_patient->save();
    }

    public function updateRNU($RNU)
    {
        $this->RNU = $RNU;
        $this->save();
    }

    public function updateFirstname($firstname)
    {
        $this->firstname = $firstname;
        $this->save();
    }

    public function updateLastname($lastname)
    {
        $this->lastname = $lastname;
        $this->save();
    }

    public function updateSex($sex)
    {
        $this->sex = $sex;
        $this->save();
    }

    public function updateDoB($DoB)
    {
        $this->DoB = $DoB;
        $this->save();
    }

    public function updateSuspect($suspect)
    {
        $this->suspect = $suspect;
        $this->save();
    }

    public function updateSuspectValidation($suspect_validation)
    {
        $this->suspect_validation = $suspect_validation;
        $this->save();
    }

    public function updateConfirmed($confirmed)
    {
        $this->confirmed = $confirmed;
        $this->save();
    }

    public function updateInvasiveCare($invasive_care)
    {
        $this->invasive_care = $invasive_care;
        $this->save();
    }
}
