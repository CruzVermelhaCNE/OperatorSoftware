<?php
declare(strict_types=1);

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
            'extensions' => ['required', 'array'],
        ];
    }
}
