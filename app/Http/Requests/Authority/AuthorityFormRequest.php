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
        $id = (int) $this->route('id');

        $stirRules = ['required'];
        if ($id > 0) {
            $stirRules[] = Rule::unique('authority', 'stir')->ignore($id);
        } else {
            $stirRules[] = Rule::unique('authority', 'stir');
        }

        return [
            'stir'    => $stirRules,
            'name_uz' => ['nullable', 'string'],
            'name_uzc'=> ['nullable', 'string'],
            'name_ru' => ['nullable', 'string'],
            'address' => ['nullable', 'string'],
        ];
    }
}
