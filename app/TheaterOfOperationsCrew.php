<?php
declare(strict_types=1);

namespace App;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class TheaterOfOperationsCrew extends Model
{
    use SoftDeletes;

    private const CACHE_BRIEF_TIMETAPE = 'TheaterOfOperations_Crews_Brief_TimeTape_';

    public static function create($name, $contact, $age, $course, $observations, $theater_of_operations_id)
    {
        if ($theater_of_operations_id == null) {
            throw new Exception('Major Event ID must be defined');
        }
        $theater_of_operations_crew                           = new TheaterOfOperationsCrew();
        $theater_of_operations_crew->name                     = $name;
        $theater_of_operations_crew->contact                  = $contact;
        $theater_of_operations_crew->age                      = $age;
        $theater_of_operations_crew->course                   = $course;
        $theater_of_operations_crew->observations             = $observations;
        $theater_of_operations_crew->theater_of_operations_id = $theater_of_operations_id;
        $theater_of_operations_crew->save();
        if ($theater_of_operations_crew->theater_of_operations->trashed()) {
            TheaterOfOperations::resetConcluded();
        } else {
            TheaterOfOperations::resetActive();
        }
        $theater_of_operations_crew->theater_of_operations->resetCrewsListing();
        return $theater_of_operations_crew;
    }

    private function insertToTimeTape($description, $type = TheaterOfOperationsTimeTape::TYPE_MODIFICATION)
    {
        $theater_of_operations_id        = null;
        $theater_of_operations_sector_id = null;
        if ($this->poi) {
            if ($this->poi->sector) {
                $theater_of_operations_sector_id = $this->poi->theater_of_operations_sector_id;
            } else {
                $theater_of_operations_id = $this->poi->theater_of_operations_id;
            }
        } else {
            if ($this->unit->sector) {
                $theater_of_operations_sector_id = $this->unit->theater_of_operations_sector_id;
            } else {
                $theater_of_operations_id = $this->unit->theater_of_operations_id;
            }
        }
        TheaterOfOperationsTimeTape::create($description, $theater_of_operations_id, $theater_of_operations_sector_id, $type);
        $this->resetBriefTimeTape();
    }

    public function generateBriefTimeTape()
    {
        $timetape = $this->theater_of_operations->time_tape()->where('description', 'LIKE', 'Operacional (#'.$this->id.'):%')->orderby('id', 'DESC')->limit(10)->get();
        $array    = [];
        foreach ($timetape as $timetape_entry) {
            $array[] = [
                $timetape_entry->date,
                $timetape_entry->description,
            ];
        }
        $data = \json_encode($array);
        Cache::put(self::CACHE_BRIEF_TIMETAPE.$this->id, $data);
        return $data;
    }

    public function resetBriefTimeTape()
    {
        if (Cache::has(self::CACHE_BRIEF_TIMETAPE.$this->id)) {
            Cache::pull(self::CACHE_BRIEF_TIMETAPE.$this->id);
        }
    }

    public function getBriefTimeTape()
    {
        $data = '';
        if (Cache::has(self::CACHE_BRIEF_TIMETAPE.$this->id)) {
            $data = Cache::get(self::CACHE_BRIEF_TIMETAPE.$this->id);
        } else {
            $data = $this->generateBriefTimeTape();
        }
        return $data;
    }

    public function getDeploymentAttribute()
    {
        if ($this->unit) {
            return $this->unit->tail_number;
        }
        if ($this->poi) {
            return $this->poi->name;
        }
        return 'Sem Destacamento';
    }

    public function getLatAttribute()
    {
        if ($this->unit) {
            return $this->unit->lat;
        }
        if ($this->poi) {
            return $this->poi->lat;
        }
        return $this->theater_of_operations->lat;
    }

    public function getLongAttribute()
    {
        if ($this->unit) {
            return $this->unit->long;
        }
        if ($this->poi) {
            return $this->poi->long;
        }
        return $this->theater_of_operations->long;
    }

    public function assignToPOI($theater_of_operations_poi_id)
    {
        if ($this->unit) {
            $this->insertToTimeTape('Operacional (#'.$this->id.'): Removido do Meio '.$this->unit->tail_number, TheaterOfOperationsTimeTape::TYPE_UNIT_MOVEMENTS);
            $this->unit->resetCrews();

            $this->theater_of_operations_unit_id = null;
        }
        if ($this->poi) {
            $this->insertToTimeTape('Operacional (#'.$this->id.'): Removido do Ponto de Interesse - '.$this->poi->name, TheaterOfOperationsTimeTape::TYPE_UNIT_MOVEMENTS);
            $this->theater_of_operations_poi_id = null;
        }
        $this->theater_of_operations_poi_id = $theater_of_operations_poi_id;
        $this->save();
        $this->load('poi');
        $this->load('unit');
        $this->insertToTimeTape('Operacional (#'.$this->id.'): Destacado para o Ponto de Interesse - '.$this->poi->name, TheaterOfOperationsTimeTape::TYPE_UNIT_MOVEMENTS);
        $this->theater_of_operations->resetCrewsListing();
    }

    public function assignToUnit($theater_of_operations_unit_id)
    {
        if ($this->unit) {
            $this->insertToTimeTape('Operacional (#'.$this->id.'): Removido da Meio '.$this->unit->tail_number, TheaterOfOperationsTimeTape::TYPE_UNIT_MOVEMENTS);
            $this->unit->resetCrews();
            $this->theater_of_operations_unit_id = null;
        }
        if ($this->poi) {
            $this->insertToTimeTape('Operacional (#'.$this->id.'): Removido do Ponto de Interesse - '.$this->poi->name, TheaterOfOperationsTimeTape::TYPE_UNIT_MOVEMENTS);
            $this->theater_of_operations_poi_id = null;
        }
        $this->theater_of_operations_unit_id = $theater_of_operations_unit_id;
        $this->save();
        $this->load('poi');
        $this->load('unit');
        $this->insertToTimeTape('Operacional (#'.$this->id.'): Destacado para o Meio '.($this->unit->tail_number?$this->unit->tail_number:$this->unit->plate), TheaterOfOperationsTimeTape::TYPE_UNIT_MOVEMENTS);
        $this->theater_of_operations->resetCrewsListing();
    }

    public function updateName($name)
    {
        $old        = $this->name;
        $this->name = $name;
        $this->save();
        $this->insertToTimeTape('Operacional (#'.$this->id.'): Nome atualizado de '.$old.' para '.$name);
        return $this;
    }

    public function updateContact($contact)
    {
        $old           = $this->contact;
        $this->contact = $contact;
        $this->save();
        $this->insertToTimeTape('Operacional (#'.$this->id.'): Contacto atualizado de '.$old.' para '.$contact);
        return $this;
    }

    public function updateAge($age)
    {
        $old       = $this->age;
        $this->age = $age;
        $this->save();
        $this->insertToTimeTape('Operacional (#'.$this->id.'): Idade atualizada de '.$old.' para '.$age);
        return $this;
    }

    public function updateCourse($course)
    {
        $old          = $this->course;
        $this->course = $course;
        $this->save();
        $this->insertToTimeTape('Operacional (#'.$this->id.'): Formação atualizada de '.$old.' para '.$course);
        return $this;
    }

    public function updateObservations($observations)
    {
        $this->observations = $observations;
        $this->save();
        return $this;
    }

    public function demobilize()
    {
        if ($this->poi) {
            $this->insertToTimeTape('Operacional (#'.$this->id.'): Removida do Ponto de Interesse - '.$this->poi->name, TheaterOfOperationsTimeTape::TYPE_UNIT_MOVEMENTS);
        }
        if ($this->unit) {
            $this->insertToTimeTape('Operacional (#'.$this->id.'): Removida do Meio '.$this->unit->tail_number, TheaterOfOperationsTimeTape::TYPE_UNIT_MOVEMENTS);
        }
        $this->insertToTimeTape('Operacional (#'.$this->id.'): Desmobilizado', TheaterOfOperationsTimeTape::TYPE_UNIT_MOVEMENTS);
        $this->delete();
        $this->theater_of_operations->resetCrewsListing();
        $this->unit->resetCrews();
    }

    public function theater_of_operations()
    {
        return $this->belongsTo(TheaterOfOperations::class, 'theater_of_operations_id', 'id')->withTrashed();
    }

    public function unit()
    {
        return $this->belongsTo(TheaterOfOperationsUnit::class, 'theater_of_operations_unit_id', 'id');
    }

    public function poi()
    {
        return $this->belongsTo(TheaterOfOperationsPOI::class, 'theater_of_operations_poi_id', 'id');
    }
}
