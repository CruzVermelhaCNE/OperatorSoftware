<?php
declare(strict_types=1);

namespace App\Http\Controllers\TheatersOfOperations;

use App\Http\Controllers\Controller;
use App\Http\Requests\TheaterOfOperationsAddToTimeTape;
use App\Http\Requests\TheaterOfOperationsCreate;
use App\Http\Requests\TheaterOfOperationsEdit;
use App\Http\Requests\TheaterOfOperationsUpdateObservations;
use App\TheaterOfOperations;
use App\TheaterOfOperationsCommunicationChannel;
use App\TheaterOfOperationsCoordination;
use App\TheaterOfOperationsCrew;
use App\TheaterOfOperationsEvent;
use App\TheaterOfOperationsPOI;
use App\TheaterOfOperationsTimeTape;
use App\TheaterOfOperationsUnit;

class TheaterOfOperationsController extends Controller
{
    public function create(TheaterOfOperationsCreate $request)
    {
        $validated = $request->validated();
        if ($validated['cdos'] == '') {
            $validated['cdos'] = null;
        }
        if ($validated['slack_channel'] == '') {
            $validated['slack_channel'] = null;
        }
        $theater_of_operations = TheaterOfOperations::create($validated['name'], $validated['type'], $validated['creation_channel'], $validated['location'], $validated['lat'], $validated['long'], $validated['level'], $validated['observations'], $validated['cdos'], $validated['slack_channel']);
        return redirect()->route('theaters_of_operations.single', $theater_of_operations->id);
    }

    public function getActive()
    {
        $data = TheaterOfOperations::getActive();
        return response($data);
    }

    public function getConcluded()
    {
        $data = TheaterOfOperations::getConcluded();
        return response($data);
    }

    public function single($id)
    {
        $theater_of_operations = TheaterOfOperations::withTrashed()->findOrFail($id);
        return view('theaters_of_operations.single', ['theater_of_operations' => $theater_of_operations]);
    }

    public function unit_redirect($unit_id)
    {
        $unit = TheaterOfOperationsUnit::findOrFail($unit_id);
        return redirect()->route('theaters_of_operations.units.single', ['id' => $unit->theater_of_operations->id, 'unit_id' => $unit_id]);
    }

    public function event_redirect($event_id)
    {
        $event = TheaterOfOperationsEvent::findOrFail($event_id);
        return redirect()->route('theaters_of_operations.event.single', ['id' => $event->theater_of_operations->id, 'event_id' => $event_id]);
    }

    public function poi_redirect($poi_id)
    {
        $poi = TheaterOfOperationsPOI::findOrFail($poi_id);
        return redirect()->route('theaters_of_operations.single', ['id' => $poi->theater_of_operations->id]);
    }

    public function info()
    {
        $theater_of_operations = TheaterOfOperations::all();
        return response()->json($theater_of_operations);
    }

    public function units_info()
    {
        $units = TheaterOfOperationsUnit::where('status', '!=', TheaterOfOperationsUnit::STATUS_DEMOBILIZED)->get();
        return response()->json($units);
    }

    public function events_info()
    {
        $events = TheaterOfOperationsEvent::whereIn('status', [TheaterOfOperationsEvent::STATUS_DISPATCH,TheaterOfOperationsEvent::STATUS_ON_GOING,TheaterOfOperationsEvent::STATUS_IN_CONCLUSION])->get(['id','type','location','lat','long']);
        return response()->json($events);
    }

    public function pois_info()
    {
        $pois = TheaterOfOperationsPOI::join('theater_of_operations', 'theater_of_operations_id', '=', 'theater_of_operations.id')->where('theater_of_operations.deleted_at', '=', null)->get(['theater_of_operations_pois.id','theater_of_operations_pois.name','theater_of_operations_pois.symbol','theater_of_operations_pois.lat','theater_of_operations_pois.long']);
        return response()->json($pois);
    }

    public function edit($id)
    {
        $theater_of_operations = TheaterOfOperations::findOrFail($id);
        return view('theaters_of_operations.edit', ['theater_of_operations' => $theater_of_operations]);
    }

    public function postEdit($id, TheaterOfOperationsEdit $request)
    {
        $theater_of_operations = TheaterOfOperations::findOrFail($id);
        $validated             = $request->validated();
        if ($validated['cdos'] == '') {
            $validated['cdos'] = null;
        }
        if ($validated['slack_channel'] == '') {
            $validated['slack_channel'] = null;
        }
        if ($theater_of_operations->name != $validated['name']) {
            $theater_of_operations->updateName($validated['name']);
        }
        if ($theater_of_operations->type != $validated['type']) {
            $theater_of_operations->updateType($validated['type']);
        }
        if ($theater_of_operations->creation_channel != $validated['creation_channel']) {
            $theater_of_operations->updateCreationChannel($validated['creation_channel']);
        }
        if ($theater_of_operations->level != $validated['level']) {
            $theater_of_operations->updateLevel($validated['level']);
        }
        if ($theater_of_operations->cdos != $validated['cdos']) {
            $theater_of_operations->updateCDOS($validated['cdos']);
        }
        if ($theater_of_operations->slack_channel != $validated['slack_channel']) {
            $theater_of_operations->updateSlackChannel($validated['slack_channel']);
        }
        if ($theater_of_operations->observations != $validated['observations']) {
            $theater_of_operations->updateObservations($validated['observations']);
        }
        if ($theater_of_operations->location != $validated['location']) {
            $theater_of_operations->updateLocation($validated['location']);
        }
        if ($theater_of_operations->lat != $validated['lat'] && $theater_of_operations->long != $validated['long']) {
            $theater_of_operations->updateGPSLocation($validated['lat'], $validated['long']);
        }
        if ($theater_of_operations->trashed()) {
            TheaterOfOperations::resetConcluded();
        } else {
            TheaterOfOperations::resetActive();
        }
        return redirect()->route('theaters_of_operations.single', $theater_of_operations->id);
    }

    public function addToTimeTape($id, TheaterOfOperationsAddToTimeTape $request)
    {
        $theater_of_operations = TheaterOfOperations::findOrFail($id);
        $validated             = $request->validated();
        $description           = '';
        if ($validated['type'] == 'to') {
            $description = 'Teatro de Operações: '.$validated['description'];
        } elseif ($validated['type'] == 'poi') {
            $poi         = TheaterOfOperationsPOI::find($validated['object']);
            $description = 'Ponto de Interesse (#'.$validated['object'].'): '.($poi->name).' '.$validated['description'];
        } elseif ($validated['type'] == 'event') {
            $description = 'Ocorrência (#'.$validated['object'].'): '.$validated['description'];
        } elseif ($validated['type'] == 'unit') {
            $unit        = TheaterOfOperationsUnit::find($validated['object']);
            $description = 'Meio (#'.$validated['object'].'): '.($unit->tail_number?$unit->tail_number:$unit->plate).' '.$validated['description'];
        } elseif ($validated['type'] == 'crew') {
            $crew        = TheaterOfOperationsCrew::find($validated['object']);
            $description = 'Operacional (#'.$validated['object'].'): '.($crew->name).' '.$validated['description'];
        }
        TheaterOfOperationsTimeTape::create($description, $theater_of_operations->id, null, TheaterOfOperationsTimeTape::TYPE_CUSTOM);
        return redirect()->route('theaters_of_operations.single', $theater_of_operations->id);
    }

    public function close($id)
    {
        $theater_of_operations = TheaterOfOperations::withTrashed()->findOrFail($id);
        if (! $theater_of_operations->trashed()) {
            if ($theater_of_operations->getUnits()->where('status', '!=', TheaterOfOperationsUnit::STATUS_DEMOBILIZED)->count() > 0) {
                return redirect()->back()->withErrors(['msg', 'Ainda existem Meios mobilizadas neste TO']);
            }
            if ($theater_of_operations->getCrews()->count() > 0) {
                return redirect()->back()->withErrors(['msg', 'Ainda existe tripulação mobilizada neste TO']);
            }
            if ($theater_of_operations->getEvents()->where('status', '!=', TheaterOfOperationsEvent::STATUS_FINISHED)->count() > 0) {
                return redirect()->back()->withErrors(['msg', 'Ainda existem ocorrências por fechar neste TO']);
            }
            $theater_of_operations->remove();
            TheaterOfOperations::resetActive();
            TheaterOfOperations::resetConcluded();
        }
        return redirect()->route('theaters_of_operations.single', $theater_of_operations->id);
    }

    public function reopen($id)
    {
        $theater_of_operations = TheaterOfOperations::withTrashed()->findOrFail($id);
        if ($theater_of_operations->trashed()) {
            $theater_of_operations->reopen();
            TheaterOfOperations::resetActive();
            TheaterOfOperations::resetConcluded();
        }
        return redirect()->route('theaters_of_operations.single', $theater_of_operations->id);
    }

    public function updateObservations($id, TheaterOfOperationsUpdateObservations $request)
    {
        $validated             = $request->validated();
        $theater_of_operations = TheaterOfOperations::findOrFail($id);
        $theater_of_operations->updateObservations($validated['observations']);
        return response('');
    }

    public function getBriefTimeTape($id)
    {
        $theater_of_operations = TheaterOfOperations::withTrashed()->findOrFail($id);
        $data                  = $theater_of_operations->getBriefTimeTape();
        return response($data);
    }

    public function getCoordination($id)
    {
        $theater_of_operations = TheaterOfOperations::withTrashed()->findOrFail($id);
        $data                  = $theater_of_operations->getCoordination();
        return response($data);
    }

    public function createCoordination($id)
    {
        $theater_of_operations = TheaterOfOperations::findOrFail($id);
        return view('theaters_of_operations.coordination.edit', ['coordination' => null,'theater_of_operations' => $theater_of_operations]);
    }

    public function editCoordination($id, $coordination_id)
    {
        $theater_of_operations = TheaterOfOperations::findOrFail($id);
        $coordination          = TheaterOfOperationsCoordination::findOrFail($coordination_id);
        return view('theaters_of_operations.coordination.edit', ['coordination' => $coordination,'theater_of_operations' => $theater_of_operations]);
    }

    public function getPOIs($id)
    {
        $theater_of_operations = TheaterOfOperations::withTrashed()->findOrFail($id);
        $data                  = $theater_of_operations->getPOIs();
        return response($data);
    }

    public function createPOI($id)
    {
        $theater_of_operations = TheaterOfOperations::findOrFail($id);
        return view('theaters_of_operations.pois.edit', ['poi' => null,'theater_of_operations' => $theater_of_operations]);
    }

    public function editPOI($id, $poi_id)
    {
        $theater_of_operations = TheaterOfOperations::findOrFail($id);
        $poi                   = TheaterOfOperationsPOI::findOrFail($poi_id);
        return view('theaters_of_operations.pois.edit', ['poi' => $poi,'theater_of_operations' => $theater_of_operations]);
    }

    public function getEvents($id)
    {
        $theater_of_operations = TheaterOfOperations::withTrashed()->findOrFail($id);
        $data                  = $theater_of_operations->getEventsListing();
        return response($data);
    }

    public function createEvent($id)
    {
        $theater_of_operations = TheaterOfOperations::findOrFail($id);
        return view('theaters_of_operations.events.edit', ['event' => null,'theater_of_operations' => $theater_of_operations]);
    }

    public function editEvent($id, $event_id)
    {
        $theater_of_operations = TheaterOfOperations::findOrFail($id);
        $event                 = TheaterOfOperationsEvent::findOrFail($event_id);
        return view('theaters_of_operations.events.edit', ['event' => $event,'theater_of_operations' => $theater_of_operations]);
    }

    public function getUnits($id)
    {
        $theater_of_operations = TheaterOfOperations::withTrashed()->findOrFail($id);
        $data                  = $theater_of_operations->getUnitsListing();
        return response($data);
    }

    public function createUnit($id)
    {
        $theater_of_operations = TheaterOfOperations::findOrFail($id);
        return view('theaters_of_operations.units.edit', ['unit' => null,'theater_of_operations' => $theater_of_operations]);
    }

    public function recreateUnit($id)
    {
        $theater_of_operations = TheaterOfOperations::findOrFail($id);
        $deleted_units         = $theater_of_operations->units()->where('status', '=', TheaterOfOperationsUnit::STATUS_DEMOBILIZED)->get();
        return view('theaters_of_operations.units.recreate', ['deleted_units' => $deleted_units,'theater_of_operations' => $theater_of_operations]);
    }

    public function editUnit($id, $unit_id)
    {
        $theater_of_operations = TheaterOfOperations::findOrFail($id);
        $unit                  = TheaterOfOperationsUnit::findOrFail($unit_id);
        return view('theaters_of_operations.units.edit', ['unit' => $unit,'theater_of_operations' => $theater_of_operations]);
    }

    public function getCrews($id)
    {
        $theater_of_operations = TheaterOfOperations::withTrashed()->findOrFail($id);
        $data                  = $theater_of_operations->getCrewsListing();
        return response($data);
    }

    public function createCrew($id)
    {
        $theater_of_operations = TheaterOfOperations::findOrFail($id);
        return view('theaters_of_operations.crews.edit', ['crew' => null,'theater_of_operations' => $theater_of_operations]);
    }

    public function recreateCrew($id)
    {
        $theater_of_operations = TheaterOfOperations::findOrFail($id);
        $deleted_crews         = $theater_of_operations->crews()->onlyTrashed()->get();
        return view('theaters_of_operations.crews.recreate', ['deleted_crews' => $deleted_crews,'theater_of_operations' => $theater_of_operations]);
    }

    public function editCrew($id, $crew_id)
    {
        $theater_of_operations = TheaterOfOperations::findOrFail($id);
        $crew                  = TheaterOfOperationsCrew::findOrFail($crew_id);
        return view('theaters_of_operations.crews.edit', ['crew' => $crew,'theater_of_operations' => $theater_of_operations]);
    }

    public function getCommunicationChannels($id)
    {
        $theater_of_operations = TheaterOfOperations::withTrashed()->findOrFail($id);
        $data                  = $theater_of_operations->getCommunicationChannels();
        return response($data);
    }

    public function createCommunicationChannel($id)
    {
        $theater_of_operations = TheaterOfOperations::findOrFail($id);
        return view('theaters_of_operations.communication_channels.edit', ['communication_channel' => null,'theater_of_operations' => $theater_of_operations]);
    }

    public function editCommunicationChannel($id, $communication_channel_id)
    {
        $theater_of_operations = TheaterOfOperations::findOrFail($id);
        $communication_channel = TheaterOfOperationsCommunicationChannel::findOrFail($communication_channel_id);
        return view('theaters_of_operations.communication_channels.edit', ['communication_channel' => $communication_channel,'theater_of_operations' => $theater_of_operations]);
    }

    public function poi_objects($id)
    {
        $theater_of_operations = TheaterOfOperations::findOrFail($id);
        $objects               = $theater_of_operations->pois()->orderBy('id', 'DESC')->get();
        return response()->json($objects);
    }

    public function event_objects($id)
    {
        $theater_of_operations = TheaterOfOperations::findOrFail($id);
        $objects               = $theater_of_operations->events()->orderBy('id', 'DESC')->get();
        return response()->json($objects);
    }

    public function unit_objects($id)
    {
        $theater_of_operations = TheaterOfOperations::findOrFail($id);
        $objects               = $theater_of_operations->units()->orderBy('id', 'DESC')->get();
        return response()->json($objects);
    }

    public function crew_objects($id)
    {
        $theater_of_operations = TheaterOfOperations::findOrFail($id);
        $objects               = $theater_of_operations->crews()->withTrashed()->orderBy('id', 'DESC')->get();
        return response()->json($objects);
    }
}
