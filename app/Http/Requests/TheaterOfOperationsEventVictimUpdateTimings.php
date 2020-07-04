<?php
declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TheaterOfOperationsEventVictimUpdateTimings extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'canceled_at'            => ['nullable','date'],
            'departure_from_scene'   => ['nullable','date'],
            'arrival_on_destination' => ['nullable','date'],
            'assisted_on_scene'      => ['nullable','date'],
            'abandoned_scene'        => ['nullable','date'],
            'refused_assistance'     => ['nullable','date'],
        ];
    }
}
