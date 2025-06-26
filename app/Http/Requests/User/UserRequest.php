<?php

namespace App\Http\Requests\User;

use App\Exceptions\Validation\ValidationException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
            'username' => [
                'required',
                'string',
                Rule::unique('users', 'username')->ignore($this->id)
            ],
            'full_name' => [
                'string'
            ],
            'password' => [
                'string'
            ],
            'pin_fl' => [
                'integer',
                Rule::unique('users', 'pin_fl')->ignore($this->id)
            ],
            'stir' => [
                'integer',
                Rule::unique('users', 'stir')->ignore($this->id),
            ],
            'auth_type' => [
                'string',
            ],
            'role' => [
                'integer',
                'required',
            ],
            'status' => [
                'integer'
            ],
            'is_juridical' => [
                'required',
                'boolean'
            ],
            'authority_id' => [
                'integer',
                Rule::exists('authority', 'id')
            ],
            'organization_id' => [
                'integer',
                'exists:organizations,id',
                'nullable'
            ],
        ];
    }
}
