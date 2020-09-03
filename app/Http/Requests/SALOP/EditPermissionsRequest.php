<?php

namespace App\Http\Requests\SALOP;

use Illuminate\Foundation\Http\FormRequest;

class EditPermissionsRequest extends FormRequest
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
            'permissions' => ['required', 'array'],
        ];
    }
}
