<?php
declare(strict_types=1);

namespace App\Http\Requests\GOI;

use Illuminate\Foundation\Http\FormRequest;

class TheaterOfOperationsCoordinationEdit extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id'           => ['nullable', 'exists:theater_of_operations_coordinations,id'],
            'name'         => ['required', 'string'],
            'role'         => ['required', 'string'],
            'contact'      => ['required', 'numeric'],
            'observations' => ['nullable', 'string'],
        ];
    }
}
