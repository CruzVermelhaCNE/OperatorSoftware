<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GDSAPI extends Controller
{
    public function getVideoURL() {
        $arrContextOptions=array(
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ),
        );  
        $xml_string = file_get_contents("https://". env('GDS3710_IP') . "/jpeg/stream?type=0&stream=0&user=". env('GDS3710_USERNAME'), false, stream_context_create($arrContextOptions));
        $xml = simplexml_load_string($xml_string);
        $json = json_encode($xml);
        $array = json_decode($json,true);
        $ChallengeCode = $array["ChallengeCode"];
        $IDCode = $array["IDCode"];
        $AuthCode = md5($ChallengeCode . ":GDS3710IDyTIHwNgZ:". env('GDS3710_PASSWORD'));
        $url = "https://". env('GDS3710_IP') . "/jpeg/stream?type=1&stream=0&user=".env('GDS3710_USERNAME')."&authcode=".$AuthCode."&idcode=".$IDCode;
        return response()->text($url);
    }
}
