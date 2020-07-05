<?php
declare(strict_types=1);

namespace App\Jobs;

use App\TheaterOfOperationsUnitGeoTracking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class GetWialonLocations implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $response = Http::get('https://hst-api.wialon.com/wialon/ajax.html', [
            'svc'    => 'token/login',
            'params' => \json_encode(['token' => ENV('WIALON_TOKEN')]),
        ]);
        $sid         = $response['eid'];
        $geotracking = TheaterOfOperationsUnitGeoTracking::where('system', '=', 'Wialon')->get();
        foreach ($geotracking as $single_geotracking) {
            $response = Http::get('https://hst-api.wialon.com/wialon/ajax.html', [
                'svc'    => 'core/search_item',
                'params' => \json_encode(['id' => $single_geotracking->external_id,'flags' => 1024]),
                'sid'    => $sid,
            ]);
            $lat  = $response['item']['pos']['y'];
            $long = $response['item']['pos']['x'];
            if ($single_geotracking->lat != $lat || $single_geotracking->long != $long) {
                $single_geotracking->updateGPSLocation($lat, $long);
            }
        }
    }
}
