<?php

namespace App\Http\Requests\Auth;

use App\Exceptions\Validation\ValidationException;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string $username
 * @property string $password
 */
class LoginRequest extends FormRequest
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
                'string'
            ],
            'password' => [
                'required',
                'string'
            ]
        ];
    }
}
