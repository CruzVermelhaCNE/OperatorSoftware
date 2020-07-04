<?php
declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TheaterOfOperationsEdit extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'             => ['required', 'string'],
            'type'             => ['required', 'string'],
            'creation_channel' => ['required', 'string'],
            'location'         => ['required', 'string'],
            'lat'              => ['required', 'numeric'],
            'long'             => ['required', 'numeric'],
            'level'            => ['required', 'string'],
            'observations'     => ['required', 'string'],
            'cdos'             => ['nullable', 'string'],
        ];
    }
}
