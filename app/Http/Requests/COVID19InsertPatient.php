<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class COVID19InsertPatient extends FormRequest
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
            "rnu" => ['string', 'nullable'],
            "lastname" => ['string', 'nullable'],
            "firstname" => ['string', 'nullable'],
            "sex" => ['boolean', 'nullable'],
            "DoB" => ['date', 'nullable'],
            "suspect" => ['required', 'boolean'],
            "suspect_validation" => ['string', 'nullable'],
            "confirmed" => ['boolean', 'nullable'],
            "invasive_care" => ['boolean', 'nullable']
        ];
    }
}
