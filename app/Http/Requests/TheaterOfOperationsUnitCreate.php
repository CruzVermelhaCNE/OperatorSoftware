<?php
declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TheaterOfOperationsUnitCreate extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'theater_of_operations_id' => ['required', 'exists:theater_of_operations,id'],
            'type'                     => ['required', 'string'],
            'plate'                    => ['nullable', 'string'],
            'tail_number'              => ['nullable', 'string'],
            'observations'             => ['nullable', 'string'],
            'structure'                => ['required', 'string'],
            'base_lat'                 => ['required', 'string'],
            'base_long'                => ['required', 'string'],
        ];
    }
}
