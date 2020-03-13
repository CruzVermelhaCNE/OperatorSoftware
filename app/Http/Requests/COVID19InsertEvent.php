<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class COVID19InsertEvent extends FormRequest
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
            "street" => ['string', 'nullable'],
            "parish" => ['string', 'nullable'],
            "county" => ['string', 'nullable'],
            "district" => ['string', 'nullable'],
            "ref" => ['string', 'nullable'],
            "source" => ['required','string'],
            "destination" => ['string', 'nullable'],
            "doctor_responsible_on_scene" => ['string', 'nullable'],
            "doctor_responsible_on_destination" => ['string', 'nullable'],
            "on_scene_units" => ['string', 'nullable'],
            "total_distance" => ['numeric', 'nullable']
        ];
    }
}
