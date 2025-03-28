<?php

namespace App\Http\Requests\Auth;

use App\Exceptions\Validation\ValidationException;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property  string $code
 */
class OneIdCodeRequest extends FormRequest
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
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'code' => strip_tags(trim($this->code))
        ]);
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'code' => ['required', 'string']
        ];
    }
}
