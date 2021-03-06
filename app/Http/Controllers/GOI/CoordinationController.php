<?php
declare(strict_types=1);

namespace App\Http\Controllers\GOI;

use App\Http\Controllers\Controller;
use App\Http\Requests\GOI\TheaterOfOperationsCoordinationCreate;
use App\Http\Requests\GOI\TheaterOfOperationsCoordinationEdit;
use App\Models\GOI\TheaterOfOperationsCoordination;

class CoordinationController extends Controller
{
    public function create(TheaterOfOperationsCoordinationCreate $request)
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
        $coordination = TheaterOfOperationsCoordination::create($validated['name'], $validated['role'], $validated['contact'], $validated['observations'], $theater_of_operations_id, $theater_of_operations_sector_id);
        if ($coordination->theater_of_operations) {
            $coordination->theater_of_operations->resetCoordination();
            return redirect()->route('goi.single', $coordination->theater_of_operations->id);
        } else {
            //return redirect()->route('goi.single', $coordination->sector->id);
        }
    }

    public function edit(TheaterOfOperationsCoordinationEdit $request)
    {
        $validated    = $request->validated();
        $coordination = TheaterOfOperationsCoordination::find($validated['id']);
        if ($coordination->name != $validated['name']) {
            $coordination->updateName($validated['name']);
        }
        if ($coordination->role != $validated['role']) {
            $coordination->updateRole($validated['role']);
        }
        if ($coordination->contact != $validated['contact']) {
            $coordination->updateContact($validated['contact']);
        }
        if ($coordination->observations != $validated['observations']) {
            $coordination->updateObservations($validated['observations']);
        }
        if ($coordination->theater_of_operations) {
            $coordination->theater_of_operations->resetCoordination();
            return redirect()->route('goi.single', $coordination->theater_of_operations->id);
        } else {
            //return redirect()->route('goi.single', $coordination->sector->id);
        }
    }

    public function remove($id, $coordination_id)
    {
        $coordination = TheaterOfOperationsCoordination::find($coordination_id);
        $coordination->remove();
        if ($coordination->theater_of_operations) {
            $coordination->theater_of_operations->resetCoordination();
            return redirect()->route('goi.single', $coordination->theater_of_operations->id);
        } else {
            //return redirect()->route('goi.single', $coordination->sector->id);
        }
    }
}
