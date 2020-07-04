<?php
declare(strict_types=1);

namespace App\Http\Controllers\TheatersOfOperations;

use App\Http\Controllers\Controller;
use App\Http\Requests\TheaterOfOperationsCrewAssignToPOI;
use App\Http\Requests\TheaterOfOperationsCrewAssignToUnit;
use App\Http\Requests\TheaterOfOperationsCrewCreate;
use App\Http\Requests\TheaterOfOperationsCrewEdit;
use App\Http\Requests\TheaterOfOperationsCrewUpdateObservations;
use App\TheaterOfOperationsCrew;
use App\TheaterOfOperationsEventUnitCrew;
use App\TheaterOfOperationsUnit;

class CrewsController extends Controller
{
    public function create(TheaterOfOperationsCrewCreate $request)
    {
        $validated = $request->validated();
        $crew      = TheaterOfOperationsCrew::create($validated['name'], $validated['contact'], $validated['age'], $validated['course'], $validated['observations'], $validated['theater_of_operations_id']);
        $crew->theater_of_operations->resetCrewsListing();
        return redirect()->route('theaters_of_operations.single', $validated['theater_of_operations_id']);
    }

    public function single($id)
    {
        $crew = TheaterOfOperationsCrew::withTrashed()->findOrFail($id);
        return view('theaters_of_operations.crews.single', ['crew' => $crew]);
    }

    public function getBriefTimeTape($id, $crew_id)
    {
        $crew = TheaterOfOperationsCrew::findOrFail($crew_id);
        $data = $crew->getBriefTimeTape();
        return response($data);
    }

    public function edit(TheaterOfOperationsCrewEdit $request)
    {
        $validated = $request->validated();
        $crew      = TheaterOfOperationsCrew::findOrFail($validated['id']);
        if ($crew->name != $validated['name']) {
            $crew->updateName($validated['name']);
        }
        if ($crew->contact != $validated['contact']) {
            $crew->updateContact($validated['contact']);
        }
        if ($crew->age != $validated['age']) {
            $crew->updateAge($validated['age']);
        }
        if ($crew->course != $validated['course']) {
            $crew->updateCourse($validated['course']);
        }
        if ($crew->observations != $validated['observations']) {
            $crew->updateObservations($validated['observations']);
        }
        $crew->theater_of_operations->resetCrewsListing();
        return redirect()->route('theaters_of_operations.crews.single', ['id' => $crew->theater_of_operations->id, 'crew_id' => $crew->id]);
    }

    public function assignToPOI($id, $crew_id, TheaterOfOperationsCrewAssignToPOI $request)
    {
        $validated = $request->validated();
        $crew      = TheaterOfOperationsCrew::findOrFail($crew_id);
        if ($crew->poi_id != $validated['poi_id']) {
            $crew->assignToPOI($validated['poi_id']);
        }
        return redirect()->route('theaters_of_operations.crews.single', ['id' => $crew->theater_of_operations->id, 'crew_id' => $crew->id]);
    }

    public function assignToUnit($id, $crew_id, TheaterOfOperationsCrewAssignToUnit $request)
    {
        $validated = $request->validated();
        $crew      = TheaterOfOperationsCrew::findOrFail($crew_id);
        $unit      = TheaterOfOperationsUnit::findOrFail($validated['unit_id']);
        if ($crew->unit_id != $validated['unit_id']) {
            $crew->assignToUnit($validated['unit_id']);
            $active_event = $unit->active_event;
            if ($active_event) {
                $event_unit = $unit->event_units()->where('theater_of_operations_event_id', '=', $active_event->id)->get()->first();
                if ($event_unit->crews->where('theater_of_operations_crew_id', '=', $crew_id)->get()->count() == 0) {
                    TheaterOfOperationsEventUnitCrew::create($event_unit->id, $crew_id);
                }
            }
        }
        return redirect()->route('theaters_of_operations.crews.single', ['id' => $crew->theater_of_operations->id, 'crew_id' => $crew->id]);
    }

    public function updateObservations($id, $crew_id, TheaterOfOperationsCrewUpdateObservations $request)
    {
        $validated = $request->validated();
        $crew      = TheaterOfOperationsCrew::findOrFail($crew_id);
        $crew->updateObservations($validated['observations']);
        return response('');
    }

    public function demobilize($id, $crew_id)
    {
        $crew = TheaterOfOperationsCrew::findOrFail($crew_id);
        $crew->demobilize();
        $crew->theater_of_operations->resetCrewsListing();
        return redirect()->route('theaters_of_operations.single', $crew->theater_of_operations->id);
    }
}
