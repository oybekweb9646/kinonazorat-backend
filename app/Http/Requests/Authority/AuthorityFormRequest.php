<?php

namespace App\Http\Requests\Authority;

use App\Exceptions\Validation\ValidationException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AuthorityFormRequest extends FormRequest
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
            'stir' => [
                'required',
                'string',
                Rule::unique('authority','name_uz')->ignore($this->id),
            ],
            'name_uz' => [
                'string',
                Rule::unique('authority','name_ru')->ignore($this->id),
            ],
            'name_uzc' => [
                'string',
                Rule::unique('authority','name_uzc')->ignore($this->id),
            ],
            'name_ru' => [
                'string',
                Rule::unique('authority','name_uzc')->ignore($this->id),
            ],
            'address' => [
                'string'
            ]
        ];
    }
}
