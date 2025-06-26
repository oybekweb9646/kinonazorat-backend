<?php

namespace App\Http\Requests\Request;

use App\Exceptions\Validation\ValidationException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrderRequest extends FormRequest
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
            'order_number' => [
                'required',
                'string',
            ],
            'order_inspector' => [
                'required',
                'string',
            ],
            'order_date' => [
                'required',
                'date',
            ],
            'order_file_id' => [
                'integer',
                Rule::exists('files', 'id')
            ],
        ];
    }
}
