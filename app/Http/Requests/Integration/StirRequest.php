<?php

namespace App\Http\Requests\Integration;

use App\Exceptions\Validation\ValidationException;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property  $stir
 */
class StirRequest extends FormRequest
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
                'required'
            ]
        ];
    }
}
