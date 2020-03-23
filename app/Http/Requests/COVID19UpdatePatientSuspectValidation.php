<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class COVID19UpdatePatientSuspectValidation extends FormRequest
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
            "patient_id" => ['required', 'exists:covid19_case_patients'],
            "suspect_validation" => ['string', 'nullable'],
        ];
    }
}
