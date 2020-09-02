<?php
declare(strict_types=1);

namespace App\Http\Controllers\TheatersOfOperations;

use App\Http\Controllers\Controller;
use App\Http\Requests\TheaterOfOperationsUnitAssignToPOI;
use App\Http\Requests\TheaterOfOperationsUnitCreate;
use App\Http\Requests\TheaterOfOperationsUnitCreateCommunicationChannel;
use App\Http\Requests\TheaterOfOperationsUnitCreateGeotracking;
use App\Http\Requests\TheaterOfOperationsUnitEdit;
use App\Http\Requests\TheaterOfOperationsUnitRecreate;
use App\Http\Requests\TheaterOfOperationsUnitUpdateCommunicationChannel;
use App\Http\Requests\TheaterOfOperationsUnitUpdateGeotracking;
use App\Http\Requests\TheaterOfOperationsUnitUpdateObservations;
use App\Http\Requests\TheaterOfOperationsUnitUpdateStatus;
use App\Models\TheaterOfOperationsEventVictim;
use App\Models\TheaterOfOperationsUnit;
use App\Models\TheaterOfOperationsUnitCommunications;
use App\Models\TheaterOfOperationsUnitGeoTracking;
use Carbon\Carbon;

class UnitsController extends Controller
{
    public function create(TheaterOfOperationsUnitCreate $request)
    {
        $validated = $request->validated();
        if ($validated['observations'] == null) {
            $validated['observations'] = '';
        }
        if ($validated['plate'] == '') {
            $validated['plate'] = null;
        }
        if ($validated['tail_number'] == '') {
            $validated['tail_number'] = null;
        }
        $unit = TheaterOfOperationsUnit::create($validated['type'], $validated['plate'], $validated['tail_number'], $validated['observations'], $validated['structure'], $validated['base_lat'], $validated['base_long'], $validated['theater_of_operations_id']);
        $unit->theater_of_operations->resetUnitsListing();
        return redirect()->route('theaters_of_operations.single', $validated['theater_of_operations_id']);
    }

    public function single($id, $unit_id)
    {
        $unit = TheaterOfOperationsUnit::findOrFail($unit_id);
        return view('theaters_of_operations.units.single', ['unit' => $unit]);
    }

    public function getBriefTimeTape($id, $unit_id)
    {
        $unit = TheaterOfOperationsUnit::findOrFail($unit_id);
        $data = $unit->getBriefTimeTape();
        return response($data);
    }

    public function getCrews($id, $unit_id)
    {
        $unit = TheaterOfOperationsUnit::findOrFail($unit_id);
        $data = $unit->getCrews();
        return response($data);
    }

    public function getCommunicationChannels($id, $unit_id)
    {
        $unit = TheaterOfOperationsUnit::findOrFail($unit_id);
        $data = $unit->getCommunicationChannels();
        return response($data);
    }

    public function createCommunicationChannel($id, $unit_id, TheaterOfOperationsUnitCreateCommunicationChannel $request)
    {
        $validated = $request->validated();
        $unit      = TheaterOfOperationsUnit::findOrFail($unit_id);
        TheaterOfOperationsUnitCommunications::create($unit_id, $validated['type'], $validated['observations']);
        $unit->resetCommunicationChannels();
        return response('');
    }

    public function getCommunicationChannel($id, $unit_id, $communication_channel_id)
    {
        $communication_channel = TheaterOfOperationsUnitCommunications::findOrFail($communication_channel_id);
        return response()->json($communication_channel);
    }

    public function updateCommunicationChannel($id, $unit_id, $communication_channel_id, TheaterOfOperationsUnitUpdateCommunicationChannel $request)
    {
        $communication_channel = TheaterOfOperationsUnitCommunications::findOrFail($communication_channel_id);
        $validated             = $request->validated();
        if ($communication_channel->type != $validated['type']) {
            $communication_channel->updateType($validated['type']);
        }
        if ($communication_channel->observations != $validated['observations']) {
            $communication_channel->updateObservations($validated['observations']);
        }
        $communication_channel->unit->resetCommunicationChannels();
        return response('');
    }

    public function removeCommunicationChannel($id, $unit_id, $communication_channel_id)
    {
        $communication_channel = TheaterOfOperationsUnitCommunications::findOrFail($communication_channel_id);
        $communication_channel->delete();
        $communication_channel->unit->resetCommunicationChannels();
        return response('');
    }

    public function getGeotracking($id, $unit_id)
    {
        $unit = TheaterOfOperationsUnit::findOrFail($unit_id);
        $data = $unit->getGeotracking();
        return response($data);
    }

    public function createGeotracking($id, $unit_id, TheaterOfOperationsUnitCreateGeotracking $request)
    {
        $validated   = $request->validated();
        $unit        = TheaterOfOperationsUnit::findOrFail($unit_id);
        $geotracking = TheaterOfOperationsUnitGeoTracking::create($unit_id, $validated['system'], '');
        $geotracking->updateExternalID($validated['external_id']);
        $unit->resetGeotracking();
        return response('');
    }

    public function getGeotrackingSingle($id, $unit_id, $geotracking_id)
    {
        $geotracking = TheaterOfOperationsUnitGeoTracking::findOrFail($geotracking_id);
        return response()->json($geotracking);
    }

    public function updateGeotracking($id, $unit_id, $geotracking_id, TheaterOfOperationsUnitUpdateGeotracking $request)
    {
        $geotracking = TheaterOfOperationsUnitGeoTracking::findOrFail($geotracking_id);
        $validated   = $request->validated();
        if ($geotracking->system != $validated['system']) {
            $geotracking->updateSystem($validated['type']);
        }
        if ($geotracking->external_id != $validated['external_id']) {
            $geotracking->updateExternalID($validated['external_id']);
        }
        $geotracking->unit->resetGeotracking();
        return response('');
    }

    public function removeGeotracking($id, $unit_id, $geotracking_id)
    {
        $geotracking = TheaterOfOperationsUnitGeoTracking::findOrFail($geotracking_id);
        $geotracking->delete();
        $geotracking->unit->resetGeotracking();
        return response('');
    }

    public function edit(TheaterOfOperationsUnitEdit $request)
    {
        $validated = $request->validated();
        $unit      = TheaterOfOperationsUnit::findOrFail($validated['id']);
        if ($validated['observations'] == '') {
            $validated['observations'] = null;
        }
        if ($validated['plate'] == '') {
            $validated['plate'] = null;
        }
        if ($validated['tail_number'] == '') {
            $validated['tail_number'] = null;
        }
        if ($unit->type != $validated['type']) {
            $unit->updateType($validated['type']);
        }
        if ($unit->plate != $validated['plate']) {
            $unit->updatePlate($validated['plate']);
        }
        if ($unit->tail_number != $validated['tail_number']) {
            $unit->updateTailNumber($validated['tail_number']);
        }
        if ($unit->observations != $validated['observations']) {
            $unit->updateObservations($validated['observations']);
        }
        if ($unit->structure != $validated['structure']) {
            $unit->updateStructure($validated['structure']);
        }
        if ($unit->base_lat != $validated['base_lat'] || $unit->base_long != $validated['base_long']) {
            $unit->updateBaseGPSLocation($validated['base_lat'], $validated['base_long']);
        }
        $unit->theater_of_operations->resetUnitsListing();
        return redirect()->route('theaters_of_operations.units.single', ['id' => $unit->theater_of_operations->id, 'unit_id' => $unit->id]);
    }

    public function recreate(TheaterOfOperationsUnitRecreate $request)
    {
        $validated = $request->validated();
        $unit      = TheaterOfOperationsUnit::find($validated['unit']);
        $unit->recreate();
        return redirect()->route('theaters_of_operations.units.single', ['id' => $unit->theater_of_operations->id, 'unit_id' => $unit->id]);
    }

    public function assignToPOI($id, $unit_id, TheaterOfOperationsUnitAssignToPOI $request)
    {
        $validated = $request->validated();
        $unit      = TheaterOfOperationsUnit::findOrFail($unit_id);
        if ($unit->poi_id != $validated['poi_id']) {
            $unit->assignToPOI($validated['poi_id']);
        }
        return redirect()->route('theaters_of_operations.units.single', ['id' => $unit->theater_of_operations->id, 'unit_id' => $unit->id]);
    }

    public function updateStatus($id, $unit_id, TheaterOfOperationsUnitUpdateStatus $request)
    {
        $validated = $request->validated();
        $unit      = TheaterOfOperationsUnit::findOrFail($unit_id);
        if ($unit->status != $validated['status']) {
            $unit->updateStatus($validated['status']);
        }
        $active_event = $unit->active_event;
        if ($active_event) {
            $event_unit = $unit->event_units()->where('theater_of_operations_event_id', '=', $active_event->id)->get()->first();
            if ($validated['status'] == TheaterOfOperationsUnit::STATUS_ON_WAY_TO_DESTINATION || $validated['status'] == TheaterOfOperationsUnit::STATUS_ON_DESTINATION) {
                $victims = $unit->active_event->victims()->where('theater_of_operations_event_unit_id', '!=', $event_unit->id)->get();
                foreach ($victims as $victim) {
                    if ($validated['status'] == TheaterOfOperationsUnit::STATUS_ON_WAY_TO_DESTINATION) {
                        $victim->updateStatus(TheaterOfOperationsEventVictim::STATUS_ON_WAY_TO_DESTINATION);
                    } elseif ($validated['status'] == TheaterOfOperationsUnit::STATUS_ON_DESTINATION) {
                        $victim->updateStatus(TheaterOfOperationsEventVictim::STATUS_ON_DESTINATION);
                    }
                }
            }
            if ($validated['status'] == TheaterOfOperationsUnit::STATUS_ON_WAY_TO_SCENE) {
                $event_unit->timings->updateOnWayToScene(Carbon::now());
            }
            if ($validated['status'] == TheaterOfOperationsUnit::STATUS_ON_SCENE) {
                $event_unit->timings->updateArrivalOnScene(Carbon::now());
            }
            if ($validated['status'] == TheaterOfOperationsUnit::STATUS_ON_WAY_TO_DESTINATION) {
                $event_unit->timings->updateDepartureFromScene(Carbon::now());
            }
            if ($validated['status'] == TheaterOfOperationsUnit::STATUS_ON_DESTINATION) {
                $event_unit->timings->updateArrivalOnDestination(Carbon::now());
            }
        }
        return redirect()->route('theaters_of_operations.units.single', ['id' => $unit->theater_of_operations->id, 'unit_id' => $unit->id]);
    }

    public function updateObservations($id, $unit_id, TheaterOfOperationsUnitUpdateObservations $request)
    {
        $validated = $request->validated();
        $unit      = TheaterOfOperationsUnit::findOrFail($unit_id);
        $unit->updateObservations($validated['observations']);
        return response('');
    }

    public function demobilize($id, $unit_id)
    {
        $unit = TheaterOfOperationsUnit::findOrFail($unit_id);
        $unit->demobilize();
        $unit->theater_of_operations->resetUnitsListing();
        return redirect()->route('theaters_of_operations.single', $unit->theater_of_operations->id);
    }
}
