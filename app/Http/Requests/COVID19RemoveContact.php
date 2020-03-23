<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class COVID19RemoveContact extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "id" => ['required', 'exists:covid19_ambulances'],
            "contact_id" => ['required', 'exists:covid19_ambulance_contacts,id'],
        ];
    }
}
