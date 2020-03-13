<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class COVID19UpdateArrivalOnDestinationStatus extends FormRequest
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
            "status_arrival_on_destination" => ['required','date'],
        ];
    }
}
