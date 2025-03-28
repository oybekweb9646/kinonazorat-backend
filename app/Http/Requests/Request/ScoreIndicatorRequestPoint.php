<?php

namespace App\Http\Requests\Request;

use App\Exceptions\Validation\ValidationException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ScoreIndicatorRequestPoint extends FormRequest
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
            'score' => [
                'boolean'
            ],
            'file_id' => [
                'integer',
                Rule::exists('files', 'id')
            ],
        ];
    }

}
