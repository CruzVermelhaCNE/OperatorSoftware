<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class COVID19NewCase extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "CODU_number" => ['required', 'numeric'],
            "CODU_localization" => ['required', 'numeric'],
            "activation_mean" => ['required', 'string']
        ];
    }
}
