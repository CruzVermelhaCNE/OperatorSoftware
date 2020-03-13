<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class COVID19InsertTeam extends FormRequest
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
            "driver_name" => ['string', 'nullable'],
            "driver_age" => ['string', 'nullable'],
            "driver_contact" => ['string', 'nullable'],
            "rescuer_name" => ['string', 'nullable'],
            "rescuer_age" => ['string', 'nullable'],
            "rescuer_contact" => ['string', 'nullable']            
        ];
    }
}
