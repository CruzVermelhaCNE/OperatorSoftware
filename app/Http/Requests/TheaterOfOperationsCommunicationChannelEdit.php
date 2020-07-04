<?php
declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TheaterOfOperationsCommunicationChannelEdit extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id'           => ['nullable', 'exists:theater_of_operations_communication_channels,id'],
            'type'         => ['required', 'string'],
            'channel'      => ['required', 'string'],
            'observations' => ['required', 'string'],
        ];
    }
}
