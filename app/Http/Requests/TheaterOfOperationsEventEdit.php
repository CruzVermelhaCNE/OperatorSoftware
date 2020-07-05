<?php
declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TheaterOfOperationsEventEdit extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id'           => ['required', 'exists:theater_of_operations_events,id'],
            'codu'         => ['nullable', 'string'],
            'cdos'         => ['nullable', 'string'],
            'type'         => ['required', 'string'],
            'observations' => ['nullable', 'string'],
            'location'     => ['required', 'string'],
            'lat'          => ['required', 'numeric'],
            'long'         => ['required', 'numeric'],
        ];
    }
}
