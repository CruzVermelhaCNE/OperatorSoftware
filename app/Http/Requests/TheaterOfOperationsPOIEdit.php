<?php
declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TheaterOfOperationsPOIEdit extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id'           => ['nullable', 'exists:theater_of_operations_pois,id'],
            'name'         => ['required', 'string'],
            'symbol'       => ['required', 'string'],
            'observations' => ['required', 'string'],
            'location'     => ['required', 'string'],
            'lat'          => ['required', 'numeric'],
            'long'         => ['required', 'numeric'],
        ];
    }
}
