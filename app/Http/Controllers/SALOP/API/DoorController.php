<?php
declare(strict_types=1);

namespace App\Http\Controllers\SALOP\API;

use App\Http\Controllers\Controller;

class DoorController extends Controller
{
    public function open()
    {
        $arrContextOptions = [
            'ssl' => [
                'verify_peer'      => false,
                'verify_peer_name' => false,
            ],
        ];
        $xml_string    = \file_get_contents('https://'.env('GDS3710_IP').'/goform/apicmd?cmd=0&user='.env('GDS3710_USERNAME'), false, \stream_context_create($arrContextOptions));
        $xml           = \simplexml_load_string($xml_string);
        $json          = \json_encode($xml);
        $array         = \json_decode($json, true);
        $ChallengeCode = $array['ChallengeCode'];
        $IDCode        = $array['IDCode'];
        $AuthCode      = \md5($ChallengeCode.':'.env('GDS3710_DOOR_CODE').':'.env('GDS3710_PASSWORD'));
        $url           = 'https://'.env('GDS3710_IP').'/goform/apicmd?cmd=1&user='.env('GDS3710_USERNAME').'&authcode='.$AuthCode.'&idcode='.$IDCode.'&type=1';
        $result        = \file_get_contents($url, false, \stream_context_create($arrContextOptions));
        return response($result, 200);
    }
}
