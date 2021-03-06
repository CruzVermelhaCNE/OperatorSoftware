<?php
declare(strict_types=1);

namespace App\Http\Requests\GOI;

use Illuminate\Foundation\Http\FormRequest;

class TheaterOfOperationsUnitCreateCommunicationChannel extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'type'         => ['required', 'string'],
            'observations' => ['nullable', 'string'],
        ];
    }
}
