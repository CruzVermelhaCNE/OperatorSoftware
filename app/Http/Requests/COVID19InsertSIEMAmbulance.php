<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class COVID19InsertSIEMAmbulance extends FormRequest
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
            "structure" => ['required', 'string'],
            "vehicle_identification" => ['required', 'string'],
            "vehicle_type" => ['required', 'numeric']
        ];
    }
}
