<?php
declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TheaterOfOperationsEventUnitUpdateTimings extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'activation'                 => ['nullable','date'],
            'on_way_to_scene'            => ['nullable','date'],
            'arrival_on_scene'           => ['nullable','date'],
            'departure_from_scene'       => ['nullable','date'],
            'arrival_on_destination'     => ['nullable','date'],
            'departure_from_destination' => ['nullable','date'],
            'available'                  => ['nullable','date'],
        ];
    }
}
