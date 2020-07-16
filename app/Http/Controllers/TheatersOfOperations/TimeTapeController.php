<?php
declare(strict_types=1);

namespace App\Http\Controllers\TheatersOfOperations;

use App\Http\Controllers\Controller;
use App\TheaterOfOperationsTimeTape;

class TimeTapeController extends Controller
{
    public function all()
    {
        $timetapes = TheaterOfOperationsTimeTape::all()->sortByDesc('id')->values();
        return response()->json($timetapes);
    }
}
