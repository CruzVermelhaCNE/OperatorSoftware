<?php
declare(strict_types=1);

namespace App\Http\Controllers\TheatersOfOperations;

use App\Http\Controllers\Controller;
use App\Http\Requests\TheaterOfOperationsCommunicationChannelCreate;
use App\Http\Requests\TheaterOfOperationsCommunicationChannelEdit;
use App\Models\TheaterOfOperationsCommunicationChannel;

class CommunicationChannelsController extends Controller
{
    public function create(TheaterOfOperationsCommunicationChannelCreate $request)
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
        $communication_channel = TheaterOfOperationsCommunicationChannel::create($validated['type'], $validated['channel'], $validated['observations'], $theater_of_operations_id, $theater_of_operations_sector_id);
        if ($communication_channel->theater_of_operations) {
            $communication_channel->theater_of_operations->resetCommunicationChannels();
            return redirect()->route('theaters_of_operations.single', $communication_channel->theater_of_operations->id);
        } else {
            //return redirect()->route('theaters_of_operations.single', $communication_channel->sector->id);
        }
    }

    public function edit(TheaterOfOperationsCommunicationChannelEdit $request)
    {
        $validated             = $request->validated();
        $communication_channel = TheaterOfOperationsCommunicationChannel::find($validated['id']);
        if ($communication_channel->type != $validated['type']) {
            $communication_channel->updateType($validated['type']);
        }
        if ($communication_channel->channel != $validated['channel']) {
            $communication_channel->updateChannel($validated['channel']);
        }
        if ($communication_channel->observations != $validated['observations']) {
            $communication_channel->updateObservations($validated['observations']);
        }
        if ($communication_channel->theater_of_operations) {
            $communication_channel->theater_of_operations->resetCommunicationChannels();
            return redirect()->route('theaters_of_operations.single', $communication_channel->theater_of_operations->id);
        } else {
            //return redirect()->route('theaters_of_operations.single', $communication_channel->sector->id);
        }
    }

    public function remove($id, $communication_channel_id)
    {
        $communication_channel = TheaterOfOperationsCommunicationChannel::find($communication_channel_id);
        $communication_channel->remove();
        if ($communication_channel->theater_of_operations) {
            $communication_channel->theater_of_operations->resetCommunicationChannels();
            return redirect()->route('theaters_of_operations.single', $communication_channel->theater_of_operations->id);
        } else {
            //return redirect()->route('theaters_of_operations.single', $communication_channel->sector->id);
        }
    }
}
