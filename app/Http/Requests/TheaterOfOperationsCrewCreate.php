<?php
declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TheaterOfOperationsCrewCreate extends FormRequest
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
            'name'                     => ['required', 'string'],
            'contact'                  => ['required', 'string'],
            'age'                      => ['required', 'string'],
            'course'                   => ['required', 'string'],
            'observations'             => ['required', 'string'],
        ];
    }
}
