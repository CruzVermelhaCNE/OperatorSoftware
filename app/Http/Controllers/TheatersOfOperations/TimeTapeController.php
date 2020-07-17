<?php
declare(strict_types=1);

namespace App\Http\Controllers\TheatersOfOperations;

use App\Http\Controllers\Controller;
use App\TheaterOfOperations;
use App\TheaterOfOperationsPOI;
use App\TheaterOfOperationsTimeTape;

class TimeTapeController extends Controller
{
    public function all()
    {
        $timetapes = TheaterOfOperationsTimeTape::orderBy('id', 'DESC')->limit(250)->get()->values();
        return response()->json($timetapes);
    }

    public function to($id)
    {
        $timetapes = TheaterOfOperationsTimeTape::where('theater_of_operations_id', '=', $id)->orderBy('id', 'DESC')->get()->values();
        return response()->json($timetapes);
    }

    public function poi($id)
    {
        $timetapes = TheaterOfOperationsTimeTape::where('description', 'LIKE', 'Ponto de Interesse (#'.$id.'):%')->orderBy('id', 'DESC')->get()->values();
        return response()->json($timetapes);
    }

    public function to_objects()
    {
        $objects = TheaterOfOperations::withTrashed()->orderBy('id', 'DESC')->get(['id','name','created_at']);
        return response()->json($objects);
    }

    public function poi_objects()
    {
        $objects = TheaterOfOperationsPOI::with('theater_of_operations')->orderBy('id', 'DESC')->get();
        return response()->json($objects);
    }
}
