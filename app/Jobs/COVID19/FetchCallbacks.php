<?php
declare(strict_types=1);

namespace App\Jobs\COVID19;

use App\Models\COVID19\Callback;
use App\Models\COVID19\CEL;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FetchCallbacks implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $import_all = false;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($import_all = false)
    {
        $this->import_all = $import_all;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $import_query = CEL::where([['context','=','custom-app-callback-1415'],['eventtype','=','HANGUP']]);
        if (! $this->import_all) {
            $last_import = Callback::orderBy('id', 'DESC')->limit(1)->get()->first();
            if ($last_import !== null) {
                $last_imported = CEL::where('uniqueid', '=', $last_import->cdr_system_id)->get()->first();
                $import_query  = $import_query->where('eventtime', '>', $last_imported->eventtime);
            }
        }
        $entries_to_import = $import_query->get();
        foreach ($entries_to_import as $entry) {
            if (Callback::where('cdr_system_id', '=', $entry->uniqueid)->get()->first() === null) {
                Callback::create($entry->uniqueid, $entry->cid_num, $entry->eventtime);
            }
        }
    }
}
