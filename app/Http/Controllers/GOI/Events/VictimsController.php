<?php
declare(strict_types=1);

namespace App\Http\Controllers\GOI\Events;

use App\Http\Controllers\Controller;
use App\Http\Requests\TheaterOfOperationsEventVictimAssignUnit;
use App\Http\Requests\TheaterOfOperationsEventVictimUpdateData;
use App\Http\Requests\TheaterOfOperationsEventVictimUpdateDestination;
use App\Http\Requests\TheaterOfOperationsEventVictimUpdateObservations;
use App\Http\Requests\TheaterOfOperationsEventVictimUpdateStatus;
use App\Http\Requests\TheaterOfOperationsEventVictimUpdateTimings;
use App\Models\TheaterOfOperationsEvent;
use App\Models\TheaterOfOperationsEventVictim;
use Carbon\Carbon;

class VictimsController extends Controller
{
    public function create($id, $event_id)
    {
        $event = TheaterOfOperationsEvent::findOrFail($event_id);
        TheaterOfOperationsEventVictim::create($event->id);
        $event->resetVictims();
        return redirect()->route('goi.events.single', ['id' => $event->theater_of_operations->id, 'event_id' => $event->id]);
    }

    public function get($id, $event_id, $victim_id)
    {
        $victim = TheaterOfOperationsEventVictim::findOrFail($victim_id);
        return response()->json($victim);
    }

    public function assignUnit($id, $event_id, $victim_id, TheaterOfOperationsEventVictimAssignUnit $request)
    {
        $victim    = TheaterOfOperationsEventVictim::findOrFail($victim_id);
        $validated = $request->validated();
        $victim->updateEventUnit($validated['event_unit']);
        return response('');
    }

    public function updateData($id, $event_id, $victim_id, TheaterOfOperationsEventVictimUpdateData $request)
    {
        $victim    = TheaterOfOperationsEventVictim::findOrFail($victim_id);
        $validated = $request->validated();
        if ($validated['age'] == '') {
            $validated['age'] = null;
        }
        if ($validated['sns'] == '') {
            $validated['sns'] = null;
        }
        if ($victim->name != $validated['name']) {
            $victim->updateName($validated['name']);
            $victim->event->resetVictims();
        }
        if ($victim->age != $validated['age']) {
            $victim->updateAge($validated['age']);
            $victim->event->resetVictims();
        }
        if ($victim->gender != $validated['gender']) {
            $victim->updateGender($validated['gender']);
            $victim->event->resetVictims();
        }
        if ($victim->sns != $validated['sns']) {
            $victim->updateSNS($validated['sns']);
            $victim->event->resetVictims();
        }
        return response('');
    }

    public function updateDestination($id, $event_id, $victim_id, TheaterOfOperationsEventVictimUpdateDestination $request)
    {
        $victim    = TheaterOfOperationsEventVictim::findOrFail($victim_id);
        $validated = $request->validated();
        if ($victim->destination != $validated['destination']) {
            $victim->updateDestination($validated['destination']);
            $victim->event->resetVictims();
        }
        if ($victim->destination_lat != $validated['destination_lat'] && $victim->destination_long != $validated['destination_long']) {
            $victim->updateGPSDestination($validated['destination_lat'], $validated['destination_long']);
            $victim->event->resetVictims();
        }
        return response('');
    }

    public function updateStatus($id, $event_id, $victim_id, TheaterOfOperationsEventVictimUpdateStatus $request)
    {
        $victim    = TheaterOfOperationsEventVictim::findOrFail($victim_id);
        $validated = $request->validated();
        if ($victim->status != $validated['status']) {
            $victim->updateStatus($validated['status']);
            if ($validated['status'] == 0) {
                $victim->updateCanceledAt(Carbon::now());
            } elseif ($validated['status'] == 2) {
                $victim->updateAssistedOnScene(Carbon::now());
            } elseif ($validated['status'] == 3) {
                $victim->updateAbandonedScene(Carbon::now());
            } elseif ($validated['status'] == 4) {
                $victim->updateRefusedAssistance(Carbon::now());
            } elseif ($validated['status'] == 5) {
                $victim->updateDepartureFromScene(Carbon::now());
            } elseif ($validated['status'] == 6) {
                $victim->updateArrivalOnDestination(Carbon::now());
            }
            $victim->event->resetVictims();
        }
        return response('');
    }

    public function updateTimings($id, $event_id, $victim_id, TheaterOfOperationsEventVictimUpdateTimings $request)
    {
        $victim    = TheaterOfOperationsEventVictim::findOrFail($victim_id);
        $validated = $request->validated();
        if (\array_key_exists('canceled_at', $validated)) {
            if ($victim->canceled_at != $validated['canceled_at']) {
                $victim->updateCanceledAt($validated['canceled_at']);
                $victim->event->resetVictims();
            }
        }
        if (\array_key_exists('departure_from_scene', $validated)) {
            if ($victim->departure_from_scene != $validated['departure_from_scene']) {
                $victim->updateDepartureFromScene($validated['departure_from_scene']);
                $victim->event->resetVictims();
            }
        }
        if (\array_key_exists('arrival_on_destination', $validated)) {
            if ($victim->arrival_on_destination != $validated['arrival_on_destination']) {
                $victim->updateArrivalOnDestination($validated['arrival_on_destination']);
                $victim->event->resetVictims();
            }
        }
        if (\array_key_exists('assisted_on_scene', $validated)) {
            if ($victim->assisted_on_scene != $validated['assisted_on_scene']) {
                $victim->updateAssistedOnScene($validated['assisted_on_scene']);
                $victim->event->resetVictims();
            }
        }
        if (\array_key_exists('refused_assistance', $validated)) {
            if ($victim->refused_assistance != $validated['refused_assistance']) {
                $victim->updateRefusedAssistance($validated['refused_assistance']);
                $victim->event->resetVictims();
            }
        }
        return response('');
    }

    public function updateObservations($id, $event_id, $victim_id, TheaterOfOperationsEventVictimUpdateObservations $request)
    {
        $victim    = TheaterOfOperationsEventVictim::findOrFail($victim_id);
        $validated = $request->validated();
        if ($victim->observations != $validated['observations']) {
            $victim->updateObservations($validated['observations']);
            $victim->event->resetVictims();
        }
        return response('');
    }

    public function delete($id, $event_id, $victim_id)
    {
        $victim = TheaterOfOperationsEventVictim::findOrFail($victim_id);
        if ($victim->name == null && $victim->age == null && $victim->sns == null && $victim->theater_of_operations_event_unit_id == null) {
            $victim->delete();
            $victim->event->resetVictims();
        }
        return redirect()->route('goi.events.single', ['id' => $victim->event->theater_of_operations->id, 'event_id' => $victim->event->id]);
    }
}
