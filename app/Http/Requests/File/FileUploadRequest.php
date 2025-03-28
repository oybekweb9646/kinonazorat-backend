<?php

namespace App\Http\Requests\File;

use App\Exceptions\Validation\ValidationException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class FileUploadRequest extends FormRequest
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
            'file' => [
                'file',
                'required',
                File::types(['png', 'jpg', 'pdf'])->max(5 * 1024)
            ]
        ];
    }
}
