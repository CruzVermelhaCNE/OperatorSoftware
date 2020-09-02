<?php
declare(strict_types=1);

namespace App\Models\GOI;

use Exception;
use Illuminate\Database\Eloquent\Model;

class TheaterOfOperationsCoordination extends Model
{
    public static function create($name, $role, $contact, $observations, $theater_of_operations_id, $theater_of_operations_sector_id)
    {
        if ($theater_of_operations_id == null && $theater_of_operations_sector_id == null) {
            throw new Exception('Major Event ID or Major Event Sector ID must be defined');
        }
        $theater_of_operations_coordination                                  = new TheaterOfOperationsCoordination();
        $theater_of_operations_coordination->name                            = $name;
        $theater_of_operations_coordination->role                            = $role;
        $theater_of_operations_coordination->contact                         = $contact;
        $theater_of_operations_coordination->observations                    = $observations;
        $theater_of_operations_coordination->theater_of_operations_id        = $theater_of_operations_id;
        $theater_of_operations_coordination->theater_of_operations_sector_id = $theater_of_operations_sector_id;
        $theater_of_operations_coordination->save();
        TheaterOfOperationsTimeTape::create('Coordenação (#'.$theater_of_operations_coordination->id.'): '.$name.' - '.$role.' adicionado', $theater_of_operations_coordination->theater_of_operations_id, $theater_of_operations_coordination->theater_of_operations_sector_id, TheaterOfOperationsTimeTape::TYPE_CREATION_DELETION);
        return $theater_of_operations_coordination;
    }

    public function updateName($name)
    {
        $old        = $this->name;
        $this->name = $name;
        $this->save();
        TheaterOfOperationsTimeTape::create('Coordenação (#'.$this->id.'): Nome atualizado de '.$old.' para '.$name, $this->theater_of_operations_id, $this->theater_of_operations_sector_id);
        return $this;
    }

    public function updateRole($role)
    {
        $old        = $this->role;
        $this->role = $role;
        $this->save();
        TheaterOfOperationsTimeTape::create('Coordenação (#'.$this->id.'): Cargo atualizado de '.$old.' para '.$role, $this->theater_of_operations_id, $this->theater_of_operations_sector_id);
        return $this;
    }

    public function updateContact($contact)
    {
        $old           = $this->contact;
        $this->contact = $contact;
        $this->save();
        TheaterOfOperationsTimeTape::create('Coordenação  (#'.$this->id.'): Contacto atualizado de '.$old.' para '.$contact, $this->theater_of_operations_id, $this->theater_of_operations_sector_id);
        return $this;
    }

    public function updateObservations($observations)
    {
        $old                = $this->observations;
        $this->observations = $observations;
        $this->save();
        return $this;
    }

    public function remove()
    {
        TheaterOfOperationsTimeTape::create('Coordenação (#'.$this->id.'): '.$this->name.' - '.$this->role.' removido', $this->theater_of_operations_id, $this->theater_of_operations_sector_id, TheaterOfOperationsTimeTape::TYPE_CREATION_DELETION);
        $this->delete();
    }

    public function theater_of_operations()
    {
        return $this->belongsTo(TheaterOfOperations::class, 'theater_of_operations_id', 'id')->withTrashed();
    }

    public function sector()
    {
        return $this->belongsTo(TheaterOfOperationsSector::class, 'theater_of_operations_sector_id', 'id');
    }
}
