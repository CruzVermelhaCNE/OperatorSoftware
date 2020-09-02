<?php
declare(strict_types=1);

namespace App\Http\Requests\GOI;

use Illuminate\Foundation\Http\FormRequest;

class TheaterOfOperationsEventVictimUpdateDestination extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'destination'      => ['required', 'string'],
            'destination_lat'  => ['required', 'numeric'],
            'destination_long' => ['required', 'numeric'],
        ];
    }
}
