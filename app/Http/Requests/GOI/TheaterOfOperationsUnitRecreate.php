<?php
declare(strict_types=1);

namespace App\Http\Requests\GOI;

use Illuminate\Foundation\Http\FormRequest;

class TheaterOfOperationsUnitRecreate extends FormRequest
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
            'unit'                     => ['required', 'exists:theater_of_operations_units,id'],
        ];
    }
}
