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
        $id = $this->route('id');

        return [
            'stir' => [
                'required',
                'string',
                Rule::unique('authority', 'stir')->ignore($id),
            ],
            'name_uz' => [
                'nullable',
                'string',
                Rule::unique('authority', 'name_uz')->ignore($id),
            ],
            'name_uzc' => [
                'nullable',
                'string',
                Rule::unique('authority', 'name_uzc')->ignore($id),
            ],
            'name_ru' => [
                'nullable',
                'string',
                Rule::unique('authority', 'name_ru')->ignore($id),
            ],
            'address' => [
                'nullable',
                'string',
            ],
        ];
    }
}
