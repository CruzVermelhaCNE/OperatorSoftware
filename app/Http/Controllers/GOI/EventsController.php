<?php
declare(strict_types=1);

namespace App\Http\Controllers\TheatersOfOperations;

use App\Http\Controllers\Controller;
use App\Http\Requests\GOI\TheaterOfOperationsEventCreate;
use App\Http\Requests\GOI\TheaterOfOperationsEventDeployUnit;
use App\Http\Requests\GOI\TheaterOfOperationsEventEdit;
use App\Http\Requests\GOI\TheaterOfOperationsEventUpdateObservations;
use App\Http\Requests\GOI\TheaterOfOperationsEventUpdateStatus;
use App\Models\TheaterOfOperationsEvent;
use App\Models\TheaterOfOperationsEventUnit;
use App\Models\TheaterOfOperationsUnit;

class EventsController extends Controller
{
    public function create(TheaterOfOperationsEventCreate $request)
    {
        $validated = $request->validated();
        if ($validated['cdos'] == '') {
            $validated['cdos'] = null;
        }
        if ($validated['codu'] == '') {
            $validated['codu'] = null;
        }
        $event = TheaterOfOperationsEvent::create($validated['codu'], $validated['cdos'], $validated['type'], TheaterOfOperationsEvent::STATUS_DISPATCH, $validated['observations'], $validated['location'], $validated['lat'], $validated['long'], $validated['theater_of_operations_id'], null);
        $event->theater_of_operations->resetEventsListing();
        return redirect()->route('goi.single', $validated['theater_of_operations_id']);
    }

    public function single($id, $event_id)
    {
        $event           = TheaterOfOperationsEvent::findOrFail($event_id);
        $available_units = null;
        if (! $event->isFinished()) {
            $available_units = TheaterOfOperationsUnit::whereIn('status', [TheaterOfOperationsUnit::STATUS_BASE,TheaterOfOperationsUnit::STATUS_ON_WAY_TO_BASE])->where('theater_of_operations_id', '=', $id)->get();
        }
        return view('goi.events.single', ['event' => $event, 'available_units' => $available_units]);
    }

    public function getBriefTimeTape($id, $event_id)
    {
        $event = TheaterOfOperationsEvent::findOrFail($event_id);
        $data  = $event->getBriefTimeTape();
        return response($data);
    }

    public function getVictims($id, $event_id)
    {
        $event = TheaterOfOperationsEvent::findOrFail($event_id);
        $data  = $event->getVictims();
        return response($data);
    }

    public function getUnits($id, $event_id)
    {
        $event = TheaterOfOperationsEvent::findOrFail($event_id);
        $data  = $event->getUnits();
        return response($data);
    }

    public function updateStatus($id, $event_id, TheaterOfOperationsEventUpdateStatus $request)
    {
        $validated = $request->validated();
        $event     = TheaterOfOperationsEvent::findOrFail($event_id);
        if ($event->status != $validated['status']) {
            $event->updateStatus($validated['status']);
            $event->theater_of_operations->resetEventsListing();
            $event->theater_of_operations->resetUnitsListing();
        }
        return redirect()->route('goi.events.single', ['id' => $event->theater_of_operations->id, 'event_id' => $event->id]);
    }

    public function updateObservations($id, $event_id, TheaterOfOperationsEventUpdateObservations $request)
    {
        $validated = $request->validated();
        $event     = TheaterOfOperationsEvent::findOrFail($event_id);
        $event->updateObservations($validated['observations']);
        return response('');
    }

    public function edit(TheaterOfOperationsEventEdit $request)
    {
        $validated = $request->validated();
        $event     = TheaterOfOperationsEvent::findOrFail($validated['id']);
        if ($validated['cdos'] == '') {
            $validated['cdos'] = null;
        }
        if ($validated['codu'] == '') {
            $validated['codu'] = null;
        }
        if ($event->cdos != $validated['cdos']) {
            $event->updateCDOS($validated['cdos']);
        }
        if ($event->codu != $validated['codu']) {
            $event->updateCODU($validated['codu']);
        }
        if ($event->type != $validated['type']) {
            $event->updateType($validated['type']);
        }
        if ($event->observations != $validated['observations']) {
            $event->updateObservations($validated['observations']);
        }
        if ($event->location != $validated['location']) {
            $event->updateLocation($validated['location']);
        }
        if ($event->lat != $validated['lat'] || $event->long != $validated['long']) {
            $event->updateGPSLocation($validated['lat'], $validated['long']);
        }
        $event->theater_of_operations->resetEventsListing();
        return redirect()->route('goi.events.single', ['id' => $event->theater_of_operations->id, 'event_id' => $event->id]);
    }

    public function deployUnit($id, $event_id, TheaterOfOperationsEventDeployUnit $request)
    {
        $validated = $request->validated();
        $event     = TheaterOfOperationsEvent::findOrFail($event_id);
        $unit      = TheaterOfOperationsUnit::findOrFail($validated['unit']);
        if ($unit->active_event == null) {
            TheaterOfOperationsEventUnit::create($event->id, $unit->id);
        }
        $unit->updateStatus(TheaterOfOperationsUnit::STATUS_DISPATCHED);
        $event->theater_of_operations->resetUnitsListing();
        return redirect()->route('goi.events.single', ['id' => $event->theater_of_operations->id, 'event_id' => $event->id]);
    }
}
