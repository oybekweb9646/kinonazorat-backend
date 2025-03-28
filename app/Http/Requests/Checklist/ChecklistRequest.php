<?php

namespace App\Http\Requests\Checklist;

use App\Exceptions\Validation\ValidationException;
use Illuminate\Foundation\Http\FormRequest;

class ChecklistRequest extends FormRequest
{
    use ValidationException;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            'name_uz' => [
                'required',
                'string'
            ],
            'indicator_type_id' => [
                'required',
                'integer'
            ],
            'name_ru' => [
                'string'
            ],
            'name_uzc' => [
                'string'
            ]
        ];
    }
}
