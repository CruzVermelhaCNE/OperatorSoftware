<?php
declare(strict_types=1);

namespace App\Http\Requests\GOI;

use Illuminate\Foundation\Http\FormRequest;

class TheaterOfOperationsPOICreate extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'theater_of_operations_id'        => ['nullable', 'exists:theater_of_operations,id'],
            'theater_of_operations_sector_id' => ['nullable', 'exists:theater_of_operations_sectors,id'],
            'name'                            => ['required', 'string'],
            'symbol'                          => ['required', 'string'],
            'observations'                    => ['nullable', 'string'],
            'location'                        => ['required', 'string'],
            'lat'                             => ['required', 'numeric'],
            'long'                            => ['required', 'numeric'],
        ];
    }
}
