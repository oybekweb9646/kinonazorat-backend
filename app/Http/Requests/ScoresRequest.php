<?php

namespace App\Http\Requests;

use App\Exceptions\Validation\ValidationException;
use App\Models\Indicator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ScoresRequest extends FormRequest
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
            'indicators' => 'required|array',
            'indicators.*.max_score' => 'required|integer|min:0',
            'indicators.*.id' => [
                'required',
                'integer',
                Rule::exists('indicators', 'id')->where(function ($query) {
                    $query->where('is_active', true);
                    $query->where('type_id', $this->type_id);
                }),
            ]
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $indicators = $this->input('indicators', []);
            if (
                count($indicators) !=
                Indicator::query()
                    ->where([
                        'is_active' => true,
                        'type_id' => $this->type_id
                    ])->count()
            ) {
                $validator->errors()->add('indicators', 'Indicators count incorrect');
            }
            $totalScore = array_reduce($indicators, function ($carry, $item) {
                return $carry + ($item['max_score'] ?? 0);
            }, 0);

            if ($totalScore != 100) {
                $validator->errors()->add('indicators', 'The total score of all indicators must be equal 100. The current total is ' . $totalScore);
            }
        });
    }
}
