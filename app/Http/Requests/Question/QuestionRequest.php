<?php

namespace App\Http\Requests\Question;

use App\Exceptions\Validation\ValidationException;
use Illuminate\Foundation\Http\FormRequest;

class QuestionRequest extends FormRequest
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
            'title_uz' => [
                'required',
                'string'
            ],
            'title_ru' => [
                'string'
            ],
            'title_uzc' => [
                'string'
            ],
            'desc_uz' => [
                'required',
                'string'
            ],
            'desc_ru' => [
                'string'
            ],
            'desc_uzc' => [
                'string'
            ]
        ];
    }
}
