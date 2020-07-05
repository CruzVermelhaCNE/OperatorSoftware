<?php
declare(strict_types=1);

namespace App\Console\Commands;

use App\TheaterOfOperationsUnitGeoTracking;
use Illuminate\Console\Command;
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
            \sleep(5);
        }
    }
}
