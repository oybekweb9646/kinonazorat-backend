<?php

namespace App\Http\Requests\Request;

use App\Core\Repository\Enum\IndicatorRepository;
use App\Exceptions\Validation\ValidationException;
use App\Models\Authority;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class RequestRequest extends FormRequest
{
    use ValidationException;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'request_no' => Str::uuid()->toString(),
        ]);
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            'request_no' => [
                'required',
                'string',
                Rule::unique('requests', 'request_no')->ignore($this->id),
            ],
            'authority_id' => [
                'required',
                'integer'
            ],
            'indicator_type_id' => [
                'integer',
                Rule::exists('indicator_types', 'id')
            ],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $indicators = app(IndicatorRepository::class)->getByIndicatorTypeId($this->input('indicator_type_id'));
            foreach ($indicators as $indicator) {
                if (!isset($indicator->max_score)) {
                    $validator->errors()->add('indicators', __('Indicator max score is required'));
                }
            }
        });
    }
}
