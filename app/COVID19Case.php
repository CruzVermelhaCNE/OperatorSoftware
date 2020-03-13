<?php
declare(strict_types=1);

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class COVID19Case extends Model
{
    /**
     * CODU Localization
     * CODU Lisboa - 1
     * CODU Porto - 2
     * CODU Coimbra - 3
     * CODU Sala de Crise - 4
     */

    /**
    * Vehicle Type
    * COVID-19 - 1
    * SIEM-PEM - 2
    * SIEM-RES - 3
    */

    use SoftDeletes;
    protected $table = 'covid19_cases';

    public function ambulance() {
        return $this->belongsTo(COVID19Ambulance::class,"case_id","id");
    }

    public static function createCase($CODU_number, $CODU_localization, $activation_mean)
    {
        $case                          = new COVID19Case();
        $case->CODU_number             = $CODU_number;
        $case->CODU_localization       = $CODU_localization;
        $case->activation_mean         = $activation_mean;
        $case->status_SALOP_activation = Carbon::now();
        $case->save();
    }

    public function addEventInformation($street, $parish, $county, $district, $ref, $source, $source_lat, $source_long, $destination, $destination_lat, $destination_long, $doctor_responsible_on_scene, $doctor_responsible_on_destination, $on_scene_units, $total_distance)
    {
        $this->street                            = $street;
        $this->parish                            = $parish;
        $this->county                            = $county;
        $this->district                          = $district;
        $this->ref                               = $ref;
        $this->source                            = $source;
        $this->source_lat                        = $source_lat;
        $this->source_long                       = $source_long;
        $this->destination                       = $destination;
        $this->destination_lat                   = $destination_lat;
        $this->destination_long                  = $destination_long;
        $this->doctor_responsible_on_scene       = $doctor_responsible_on_scene;
        $this->doctor_responsible_on_destination = $doctor_responsible_on_destination;
        $this->on_scene_units                    = $on_scene_units;
        $this->total_distance                    = $total_distance;
        $this->save();
    }

    public function addTeamInformation($driver_name, $driver_age, $driver_contact, $rescuer_name, $rescuer_age, $rescuer_contact)
    {
        $this->driver_name     = $driver_name;
        $this->driver_age      = $driver_age;
        $this->driver_contact  = $driver_contact;
        $this->rescuer_name    = $rescuer_name;
        $this->rescuer_age     = $rescuer_age;
        $this->rescuer_contact = $rescuer_contact;
        $this->save();
    }

    public function addPatientInformation($RNU, $firstname, $lastname, $sex, $DoB, $suspect, $suspect_validation, $confirmed, $invasive_care)
    {
        $this->RNU                = $RNU;
        $this->firstname          = $firstname;
        $this->lastname           = $lastname;
        $this->sex                = $sex;
        $this->DoB                = $DoB;
        $this->suspect            = $suspect;
        $this->suspect_validation = $suspect_validation;
        $this->confirmed          = $confirmed;
        $this->invasive_care      = $invasive_care;
        $this->save();
    }

    public function addVehicleInformation($structure, $vehicle_identification, $vehicle_type)
    {
        $this->structure              = $structure;
        $this->vehicle_identification = $vehicle_identification;
        $this->vehicle_type           = $vehicle_type;
        $this->save();
    }

    public function statusActivation($status_AMB_activation)
    {
        $this->status_AMB_activation  = $status_AMB_activation;
        $this->save();
    }

    public function statusBaseExit($status_base_exit)
    {
        $this->status_base_exit = $status_base_exit;
        $this->save();
    }

    public function statusArrivalOnScene($status_arrival_on_scene)
    {
        $this->status_arrival_on_scene = $status_arrival_on_scene;
        $this->save();
    }

    public function statusDepartureFromScene($status_departure_from_scene)
    {
        $this->status_departure_from_scene = $status_departure_from_scene;
        $this->save();
    }

    public function statusArrivalOnDestination($status_arrival_on_destination)
    {
        $this->status_arrival_on_destination = $status_arrival_on_destination;
        $this->save();
    }

    public function statusDepartureFromDestination($status_departure_from_destination)
    {
        $this->status_departure_from_destination = $status_departure_from_destination;
        $this->save();
    }

    public function statusBaseReturn($status_base_return)
    {
        $this->status_base_return = $status_base_return;
        $this->save();
    }

    public function statusAvailable($status_available)
    {
        $this->status_available = $status_available;
        $this->save();
    }

    public function updateCODUNumber($CODU_number)
    {
        $this->CODU_number = $CODU_number;
        $this->save();
    }

    public function updateCODULocalization($CODU_localization)
    {
        $this->CODU_localization = $CODU_localization;
        $this->save();
    }

    public function updateActivationMean($activation_mean)
    {
        $this->activation_mean = $activation_mean;
        $this->save();
    }

    public function updateSALOPActivationDateTime($status_SALOP_activation)
    {
        $this->status_SALOP_activation = $status_SALOP_activation;
        $this->save();
    }

    public function updateRNU($RNU)
    {
        $this->RNU = $RNU;
        $this->save();
    }

    public function updateLastName($lastname)
    {
        $this->lastname = $lastname;
        $this->save();
    }

    public function updateFirstName($firstname)
    {
        $this->firstname = $firstname;
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

    public function updateStreet($street)
    {
        $this->street = $street;
        $this->save();
    }

    public function updateRef($ref)
    {
        $this->ref = $ref;
        $this->save();
    }

    public function updateParish($parish)
    {
        $this->parish = $parish;
        $this->save();
    }

    public function updateCounty($county)
    {
        $this->county = $county;
        $this->save();
    }

    public function updateDistrict($district)
    {
        $this->district = $district;
        $this->save();
    }

    public function updateSource($source)
    {
        $this->source = $source;
        $this->save();
    }

    public function updateDestination($destination)
    {
        $this->destination = $destination;
        $this->save();
    }

    public function updateDoctorResponsibleOnScene($doctor_responsible_on_scene)
    {
        $this->doctor_responsible_on_scene = $doctor_responsible_on_scene;
        $this->save();
    }

    public function updateDoctorResponsibleOnDestination($doctor_responsible_on_destination)
    {
        $this->doctor_responsible_on_destination = $doctor_responsible_on_destination;
        $this->save();
    }

    public function updateOnSceneUnits($on_scene_units)
    {
        $this->on_scene_units = $on_scene_units;
        $this->save();
    }

    public function updateTotalDistance($total_distance)
    {
        $this->total_distance = $total_distance;
        $this->save();
    }

    public function updateDriverName($driver_name)
    {
        $this->driver_name = $driver_name;
        $this->save();
    }

    public function updateDriverAge($driver_age)
    {
        $this->driver_age = $driver_age;
        $this->save();
    }

    public function updateDriverContact($driver_contact)
    {
        $this->driver_contact = $driver_contact;
        $this->save();
    }

    public function updateRescuerName($rescuer_name)
    {
        $this->rescuer_name = $rescuer_name;
        $this->save();
    }

    public function updateRescuerAge($rescuer_age)
    {
        $this->rescuer_age = $rescuer_age;
        $this->save();
    }

    public function updateRescuerContact($rescuer_contact)
    {
        $this->rescuer_contact = $rescuer_contact;
        $this->save();
    }

    public function updateSALOPActivationStatus($status_SALOP_activation) {
        $this->status_SALOP_activation = $status_SALOP_activation;
        $this->save();
    }

    public function updateAMBActivationStatus($status_AMB_activation) {
        $this->status_AMB_activation = $status_AMB_activation;
        $this->save();
    }

    public function updateBaseExitStatus($status_base_exit) {
        $this->status_base_exit = $status_base_exit;
        $this->save();
    }

    public function updateArrivalOnSceneStatus($status_arrival_on_scene) {
        $this->status_arrival_on_scene = $status_arrival_on_scene;
        $this->save();
    }

    public function updateDepartureFromSceneStatus($status_departure_from_scene) {
        $this->status_departure_from_scene = $status_departure_from_scene;
        $this->save();
    }

    public function updateArrivalOnDestinationStatus($status_arrival_on_destination) {
        $this->status_arrival_on_destination = $status_arrival_on_destination;
        $this->save();
    }

    public function updateDepartureFromDestinationStatus($status_departure_from_destination) {
        $this->status_departure_from_destination = $status_departure_from_destination;
        $this->save();
    }

    public function updateBaseReturnStatus($status_base_return) {
        $this->status_base_return = $status_base_return;
        $this->save();
    }

    public function complete_source() {
        return $this->street . ", " . $this->parish . ", ". $this->county . ', '. $this->district;
    }
}
