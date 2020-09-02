<?php
declare(strict_types=1);

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;

class TheaterOfOperationsCommunicationChannel extends Model
{
    public static function create($type, $channel, $observations, $theater_of_operations_id, $theater_of_operations_sector_id)
    {
        if ($theater_of_operations_id == null && $theater_of_operations_sector_id == null) {
            throw new Exception('Major Event ID or Major Event Sector ID must be defined');
        }
        $theater_of_operations_communications_channel                                  = new TheaterOfOperationsCommunicationChannel();
        $theater_of_operations_communications_channel->type                            = $type;
        $theater_of_operations_communications_channel->channel                         = $channel;
        $theater_of_operations_communications_channel->observations                    = $observations;
        $theater_of_operations_communications_channel->theater_of_operations_id        = $theater_of_operations_id;
        $theater_of_operations_communications_channel->theater_of_operations_sector_id = $theater_of_operations_sector_id;
        $theater_of_operations_communications_channel->save();
        TheaterOfOperationsTimeTape::create('Canal de Comunicações (#'.$theater_of_operations_communications_channel->id.'): '.$type.' - '.$channel.' adicionado', $theater_of_operations_communications_channel->theater_of_operations_id, $theater_of_operations_communications_channel->theater_of_operations_sector_id, TheaterOfOperationsTimeTape::TYPE_CREATION_DELETION);
        return $theater_of_operations_communications_channel;
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
        TheaterOfOperationsTimeTape::create('Canal de Comunicações (#'.$this->id.'): '.$this->type.' - '.$this->channel.' removido', $this->theater_of_operations_id, $this->theater_of_operations_sector_id, TheaterOfOperationsTimeTape::TYPE_CREATION_DELETION);
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
