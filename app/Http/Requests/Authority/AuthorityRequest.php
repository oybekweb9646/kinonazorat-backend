<?php

namespace App\Http\Requests\Authority;

use App\Exceptions\Validation\ValidationException;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class AuthorityRequest
 *
 * @package App\Http\Requests
 *
 * @property-read array $data Ma'lumotlar massiv ko'rinishida
 *
 * Strukturasi:
 * - authority_name (string) → Tashkilot nomi
 * - authority_inn (int) → Tashkilot INN (faqat raqam)
 * - authority_address (string) → Tashkilot manzili
 */
class AuthorityRequest extends FormRequest
{
    use ValidationException;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'data' => ['required', 'array', 'min:1'], // 'data' massiv bo‘lishi shart
            'data.*.authority_name' => ['required', 'string'], // Majburiy, string, max 255
            'data.*.authority_inn' => ['required', 'integer', 'digits_between:9,12'], // 9-12 xonali raqam bo‘lishi shart
            'data.*.authority_address' => ['required', 'string', 'max:500'], // Majburiy, string, max 500 ta belgidan iborat
        ];
    }

    /**
     * Xatolik xabarlari uchun custom message lar
     */
    public function messages(): array
    {
        return [
            'data.required' => 'Ma’lumotlar kerak!',
            'data.array' => 'Ma’lumotlar massiv bo‘lishi kerak!',
            'data.min' => 'Hech bo‘lmaganda bitta obyekt bo‘lishi kerak!',
            'data.*.authority_name.required' => 'Tashkilot nomi majburiy!',
            'data.*.authority_name.string' => 'Tashkilot nomi faqat harflardan iborat bo‘lishi kerak!',
            'data.*.authority_name.max' => 'Tashkilot nomi 255 ta belgidan oshmasligi kerak!',
            'data.*.authority_inn.required' => 'INN majburiy!',
            'data.*.authority_inn.integer' => 'INN faqat raqamlardan iborat bo‘lishi kerak!',
            'data.*.authority_inn.digits_between' => 'INN 9 dan 12 gacha raqam bo‘lishi kerak!',
            'data.*.authority_address.required' => 'Tashkilot manzili majburiy!',
            'data.*.authority_address.string' => 'Tashkilot manzili matn ko‘rinishida bo‘lishi kerak!',
            'data.*.authority_address.max' => 'Tashkilot manzili 500 ta belgidan oshmasligi kerak!',
        ];
    }
}
