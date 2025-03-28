<?php

namespace App\Exceptions\Validation;

use App\Core\Helpers\Responses\ValidationErrorResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

trait ValidationException
{
    /**
     * @param Validator $validator
     * @return void
     */
    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(ValidationErrorResponse::send($validator));
    }
}
