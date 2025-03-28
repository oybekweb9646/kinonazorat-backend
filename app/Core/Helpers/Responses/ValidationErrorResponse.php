<?php

namespace App\Core\Helpers\Responses;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Lang;

class ValidationErrorResponse
{
    private static array $messages = [];

    public static function sendSingleError(Validator $validator)
    {
    }

    public static function sendMultipleError()
    {

    }

    /**
     * @param Validator $validator
     * @return JsonResponse
     */
    public static function send(Validator $validator): JsonResponse
    {
        self::recursiveFunc($validator->getMessageBag()->getMessages());

        return response()->json([
            'success' => false,
            'message' => Lang::get('validate error'),
            'data'    => self::$messages
        ], 422);
    }

    /**
     * @param array $errors
     * @param string|null $key
     * @return void
     */
    public static function recursiveFunc(array $errors, ?string $key = null): void
    {
        foreach ($errors as $k => $error) {
            foreach ($error as $item) {
                self::$messages[] = [
                    'field'   => $k,
                    'message' => $item
                ];
            }
        }
    }
}
