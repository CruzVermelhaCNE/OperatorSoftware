<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class COVID19UpdateDriverContact extends FormRequest
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
            "driver_contact" => ['string', 'nullable'],
        ];
    }
}
