<?php

namespace App\Http\Requests\SALOP;

use Illuminate\Foundation\Http\FormRequest;

class EditExtensionsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user'        => ['required', 'exists:users,id'],
            'extensions' => ['required', 'array'],
        ];
    }
}
