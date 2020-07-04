<?php
declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TheaterOfOperationsUnitUpdateGeotracking extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'system'      => ['required', 'string'],
            'external_id' => ['required', 'string'],
        ];
    }
}
