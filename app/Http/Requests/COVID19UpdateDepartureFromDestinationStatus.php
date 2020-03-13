<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class COVID19UpdateDepartureFromDestinationStatus extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "id" => ['required', 'exists:covid19_cases'],
            "status_departure_from_destination" => ['required','date'],
        ];
    }
}
