<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class COVID19NewAmbulance extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "structure" => ['required', 'string'],
            "region" => ['required', 'string'],
            "vehicle_identification" => ['required', 'string'],
            "active_prevention" => ['required', 'boolean']
        ];
    }
}
