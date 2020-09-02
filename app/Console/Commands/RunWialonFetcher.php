<?php
declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\GOI\TheaterOfOperationsUnit;
use App\Models\GOI\TheaterOfOperationsUnitGeoTracking;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class RunWialonFetcher extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:wialonfetcher';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run the Wialon fetcher';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        echo("Running wialon fetcher\n");
        $response = Http::get('https://hst-api.wialon.com/wialon/ajax.html', [
            'svc'    => 'token/login',
            'params' => \json_encode(['token' => ENV('WIALON_TOKEN')]),
        ]);
        $sid = $response['eid'];
        while (true) {
            $geotracking = DB::table('theater_of_operations_unit_geo_trackings')->join('theater_of_operations_units', 'theater_of_operations_unit_id', '=', 'theater_of_operations_units.id')->where([['system','=','Wialon'],['theater_of_operations_units.status','!=',TheaterOfOperationsUnit::STATUS_DEMOBILIZED]])->get();
            foreach ($geotracking as $single_geotracking) {
                $response = Http::get('https://hst-api.wialon.com/wialon/ajax.html', [
                    'svc'    => 'core/search_item',
                    'params' => \json_encode(['id' => $single_geotracking->external_id,'flags' => 1024]),
                    'sid'    => $sid,
                ]);
                $lat                = $response['item']['pos']['y'];
                $long               = $response['item']['pos']['x'];
                $geotracking_object = TheaterOfOperationsUnitGeoTracking::where('external_id', '=', $single_geotracking->external_id)->get()->first();
                if ($geotracking_object->lat != $lat || $geotracking_object->long != $long) {
                    $geotracking_object->updateGPSLocation($lat, $long);
                }
            }
            \sleep(5);
        }
    }
}
