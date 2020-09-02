<?php
declare(strict_types=1);

namespace App\Http\Requests\GOI;

use Illuminate\Foundation\Http\FormRequest;

class TheaterOfOperationsUnitEdit extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id'           => ['required', 'exists:theater_of_operations_units,id'],
            'type'         => ['required', 'string'],
            'plate'        => ['nullable', 'string'],
            'tail_number'  => ['nullable', 'string'],
            'observations' => ['nullable', 'string'],
            'structure'    => ['required', 'string'],
            'base_lat'     => ['required', 'string'],
            'base_long'    => ['required', 'string'],
        ];
    }
}
