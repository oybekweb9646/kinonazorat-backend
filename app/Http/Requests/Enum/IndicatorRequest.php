<?php

namespace App\Http\Requests\Enum;

use App\Exceptions\Validation\ValidationException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndicatorRequest extends FormRequest
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
                Rule::unique('indicators','name_uz')->where(function ($query) {
                    return $query->where('type_id', $this->input('type_id'));
                })->ignore($this->id),
            ],
            'name_ru' => [
                'string',
                Rule::unique('indicators','name_ru')->where(function ($query) {
                    return $query->where('type_id', $this->input('type_id'));
                })->ignore($this->id),
            ],
            'name_uzc' => [
                'string',
                Rule::unique('indicators','name_uzc')->where(function ($query) {
                    return $query->where('type_id', $this->input('type_id'));
                })->ignore($this->id),
            ],
            'type_id' => [
                'required',
                'integer',
                Rule::exists('indicator_types', 'id')
            ],
            'max_score' => [
                'integer'
            ],
            'is_active' => [
                'boolean'
            ]
        ];
    }
}
