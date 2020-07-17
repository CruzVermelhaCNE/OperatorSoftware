<?php
declare(strict_types=1);

namespace App\Http\Controllers\TheatersOfOperations;

use App\Http\Controllers\Controller;
use App\TheaterOfOperations;
use App\TheaterOfOperationsTimeTape;

class TimeTapeController extends Controller
{
    public function all()
    {
        $timetapes = TheaterOfOperationsTimeTape::orderBy('id', 'DESC')->limit(250)->get()->values();
        return response()->json($timetapes);
    }

    public function to_objects()
    {
        $objects = TheaterOfOperations::withTrashed()->orderBy('id', 'DESC')->get(['id','name','created_at']);
        return response()->json($objects);
    }
}
