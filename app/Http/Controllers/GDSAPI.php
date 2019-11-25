<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GDSAPI extends Controller
{
    public function getAuthCode() {
        $challenge_code = Input::get('cc');
        $auth_code = md5($challenge_code . ":GDS3710IDyTIHwNgZ:". env('GDS3710_PASSWORD'));
        return response()->text($auth_code);
    }
}
