<?php
declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TheaterOfOperationsEventVictimUpdateData extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'   => ['required', 'string'],
            'age'    => ['required', 'numeric'],
            'gender' => ['required', 'numeric'],
            'sns'    => ['required', 'numeric'],
        ];
    }
}