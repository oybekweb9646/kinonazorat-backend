<?php

namespace App\Http\Requests\Enum;

use App\Exceptions\Validation\ValidationException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndicatorTypeRequest extends FormRequest
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
                'string',
                Rule::unique('indicator_types', 'name_uz')->ignore($this->id),
            ],
            'name_ru' => [
                'string',
                Rule::unique('indicator_types', 'name_ru')->ignore($this->id),
            ],
            'name_uzc' => [
                'string',
                Rule::unique('indicator_types', 'name_uzc')->ignore($this->id),
            ]
        ];
    }
}
