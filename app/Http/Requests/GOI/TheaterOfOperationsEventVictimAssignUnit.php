<?php
declare(strict_types=1);

namespace App\Http\Requests\GOI;

use Illuminate\Foundation\Http\FormRequest;

class TheaterOfOperationsEventVictimAssignUnit extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'event_unit' => ['required', 'exists:theater_of_operations_event_units,id'],
        ];
    }
}
