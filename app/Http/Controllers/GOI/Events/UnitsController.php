<?php
declare(strict_types=1);

namespace App\Http\Controllers\GOI\Events;

use App\Http\Controllers\Controller;
use App\Http\Requests\GOI\TheaterOfOperationsEventUnitUpdateTimings;
use App\Models\GOI\TheaterOfOperationsEventUnit;

class UnitsController extends Controller
{
    public function get($id, $event_id, $event_unit_id)
    {
        $event_unit = TheaterOfOperationsEventUnit::with(['unit','event','timings'])->findOrFail($event_unit_id);
        return response()->json($event_unit);
    }

    public function updateTimings($id, $event_id, $event_unit_id, TheaterOfOperationsEventUnitUpdateTimings $request)
    {
        $event_unit_timings = TheaterOfOperationsEventUnit::findOrFail($event_unit_id)->timings;
        $validated          = $request->validated();
        if (\array_key_exists('activation', $validated)) {
            if ($event_unit_timings->activation != $validated['activation']) {
                $event_unit_timings->updateActivation($validated['activation']);
            }
        }
        if (\array_key_exists('on_way_to_scene', $validated)) {
            if ($event_unit_timings->on_way_to_scene != $validated['on_way_to_scene']) {
                $event_unit_timings->updateOnWayToScene($validated['on_way_to_scene']);
            }
        }
        if (\array_key_exists('arrival_on_scene', $validated)) {
            if ($event_unit_timings->arrival_on_scene != $validated['arrival_on_scene']) {
                $event_unit_timings->updateArrivalOnScene($validated['arrival_on_scene']);
            }
        }
        if (\array_key_exists('departure_from_scene', $validated)) {
            if ($event_unit_timings->departure_from_scene != $validated['departure_from_scene']) {
                $event_unit_timings->updateDepartureFromScene($validated['departure_from_scene']);
            }
        }
        if (\array_key_exists('arrival_on_destination', $validated)) {
            if ($event_unit_timings->arrival_on_destination != $validated['arrival_on_destination']) {
                $event_unit_timings->updateArrivalOnDestination($validated['arrival_on_destination']);
            }
        }
        if (\array_key_exists('departure_from_destination', $validated)) {
            if ($event_unit_timings->departure_from_destination != $validated['departure_from_destination']) {
                $event_unit_timings->updateDepartureFromDestination($validated['departure_from_destination']);
            }
        }
        if (\array_key_exists('available', $validated)) {
            if ($event_unit_timings->available != $validated['available']) {
                $event_unit_timings->updateAvailable($validated['available']);
            }
        }
        return response('');
    }
}
