<?php

namespace App\Core\Helpers\Responses;

use Illuminate\Http\JsonResponse;

class Response
{
    /**
     * @param string $message
     * @param array $data
     * @param int $statusCode
     * @return JsonResponse
     */
    public static function success(string $message = 'Successful saved',  $data = [], int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'code' => $statusCode,
            'success' => true,
            'message' => __('client.' . $message),
            'data' => $data
        ]);
    }

    /**
     * @param string $message
     * @param array $data
     * @param int $statusCode
     * @return JsonResponse
     */
    public function error(string $message = 'Unknown error', array $data = [], int $statusCode = 400): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => __('client.' . $message),
            'data' => $data,
            'code' => $statusCode
        ]);
    }
}
