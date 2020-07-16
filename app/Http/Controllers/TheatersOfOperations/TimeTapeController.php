<?php
declare(strict_types=1);

namespace App\Http\Controllers\TheatersOfOperations;

use App\Http\Controllers\Controller;
use App\TheaterOfOperations;

class TimeTapeController extends Controller
{
    public function all()
    {
        $timetapes = TheaterOfOperations::all();
        return response()->json($timetapes);
    }
}
