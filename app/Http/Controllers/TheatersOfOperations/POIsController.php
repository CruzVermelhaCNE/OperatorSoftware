<?php
declare(strict_types=1);

namespace App\Http\Controllers\TheatersOfOperations;

use App\Http\Controllers\Controller;
use App\Http\Requests\TheaterOfOperationsPOICreate;
use App\Http\Requests\TheaterOfOperationsPOIEdit;
use App\Models\TheaterOfOperationsPOI;

class POIsController extends Controller
{
    public function create(TheaterOfOperationsPOICreate $request)
    {
        $validated                = $request->validated();
        $theater_of_operations_id = null;
        if (\array_key_exists('theater_of_operations_id', $validated)) {
            $theater_of_operations_id = $validated['theater_of_operations_id'];
        }
        $theater_of_operations_sector_id = null;
        if (\array_key_exists('theater_of_operations_sector_id', $validated)) {
            $theater_of_operations_sector_id = $validated['theater_of_operations_sector_id'];
        }
        $poi = TheaterOfOperationsPOI::create($validated['name'], $validated['location'], $validated['lat'], $validated['long'], $validated['symbol'], $validated['observations'], $theater_of_operations_id, $theater_of_operations_sector_id);
        if ($poi->theater_of_operations) {
            $poi->theater_of_operations->resetPOIs();
            return redirect()->route('theaters_of_operations.single', $poi->theater_of_operations->id);
        } else {
            //return redirect()->route('theaters_of_operations.single', $poi->sector->id);
        }
    }

    public function edit(TheaterOfOperationsPOIEdit $request)
    {
        $validated = $request->validated();
        $poi       = TheaterOfOperationsPOI::find($validated['id']);
        if ($poi->name != $validated['name']) {
            $poi->updateName($validated['name']);
        }
        if ($poi->symbol != $validated['symbol']) {
            $poi->updateSymbol($validated['symbol']);
        }
        if ($poi->observations != $validated['observations']) {
            $poi->updateObservations($validated['observations']);
        }
        if ($poi->location != $validated['location']) {
            $poi->updateLocation($validated['location']);
        }
        if ($poi->lat != $validated['lat'] && $poi->long != $validated['long']) {
            $poi->updateGPSLocation($validated['lat'], $validated['long']);
        }
        if ($poi->theater_of_operations) {
            $poi->theater_of_operations->resetPOIs();
            return redirect()->route('theaters_of_operations.single', $poi->theater_of_operations->id);
        } else {
            //return redirect()->route('theaters_of_operations.single', $poi->sector->id);
        }
    }

    public function remove($id, $poi_id)
    {
        $poi = TheaterOfOperationsPOI::find($poi_id);
        $poi->remove();
        if ($poi->theater_of_operations) {
            $poi->theater_of_operations->resetPOIs();
            return redirect()->route('theaters_of_operations.single', $poi->theater_of_operations->id);
        } else {
            //return redirect()->route('theaters_of_operations.single', $poi->sector->id);
        }
    }
}
