<?php
declare(strict_types=1);

namespace App;

use App\Notifications\TheaterOfOperationsSlackNotification;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Model;

class TheaterOfOperationsTimeTape extends Model
{
    public const TYPE_CUSTOM            = 0;
    public const TYPE_MODIFICATION      = 1;
    public const TYPE_CREATION_DELETION = 2;
    public const TYPE_UNIT_MOVEMENTS    = 3;

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public static function create($description, $theater_of_operations_id, $theater_of_operations_sector_id, $type = self::TYPE_MODIFICATION)
    {
        if ($theater_of_operations_id == null && $theater_of_operations_sector_id == null) {
            throw new Exception('Major Event ID or Major Event Sector ID must be defined');
        }
        $time_tape                                  = new TheaterOfOperationsTimeTape();
        $time_tape->date                            = Carbon::now();
        $time_tape->description                     = $description;
        $time_tape->theater_of_operations_id        = $theater_of_operations_id;
        $time_tape->theater_of_operations_sector_id = $theater_of_operations_sector_id;
        $time_tape->type                            = $type;
        $time_tape->save();
        if ($type == self::TYPE_CREATION_DELETION || $type == self::TYPE_CUSTOM) {
            $time_tape->theater_of_operations->resetBriefTimeTape();
        }
        if ($time_tape->theater_of_operations->slack_channel) {
            $time_tape->theater_of_operations->notify((new TheaterOfOperationsSlackNotification($description)));
        }
        return $time_tape;
    }

    public function theater_of_operations()
    {
        return $this->belongsTo(TheaterOfOperations::class, 'theater_of_operations_id', 'id')->withTrashed();
    }

    public function sector()
    {
        return $this->belongsTo(TheaterOfOperationsSector::class, 'theater_of_operations_sector_id', 'id')->withTrashed();
    }
}
