<?php

namespace App\Http\Requests\Request;

use App\Exceptions\Validation\ValidationException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ActRequest extends FormRequest
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
            'act_number' => [
                'required',
                'string',
            ],
            'act_date' => [
                'required',
                'date',
            ],
            'act_file_id' => [
                'required',
                'integer',
                Rule::exists('files', 'id')
            ],
        ];
    }
}
