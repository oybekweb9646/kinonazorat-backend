<?php

namespace App\Http\Controllers\Auth;

use App\Core\Helpers\Lang\LanguageHelper;
use App\Core\Helpers\Responses\Response;
use App\Core\Service\Auth\Contracts\AuthContract;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\OneIdCodeRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct(
        protected AuthContract $authService
    )
    {
    }

    /**
     * @param OneIdCodeRequest $oneIdCodeRequest
     * @return JsonResponse
     */
    public function loginByOneId(OneIdCodeRequest $oneIdCodeRequest): JsonResponse
    {
        $jwtToken = $this->authService->loginByOneId($oneIdCodeRequest);

        return Response::success('Authenticated user', [
            'access_token' => $jwtToken->getAccessToken(),
            'token_type' => 'bearer'
        ]);
    }

    /**
     * @param LoginRequest $loginRequest
     * @return JsonResponse
     */
    public function loginByUser(LoginRequest $loginRequest): JsonResponse
    {
        $jwtToken = $this->authService->loginByUser($loginRequest);

        return Response::success('Authenticated user', [
            'access_token' => $jwtToken->getAccessToken(),
            'token_type' => 'bearer'
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function refreshToken(Request $request): JsonResponse
    {
        $jwtToken = $this->authService->refreshToken($request);

        return Response::success('Authenticated user', [
            'access_token' => $jwtToken->getAccessToken(),
            'token_type' => 'bearer'
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        $this->authService->logout();
        return Response::success('Logout user');
    }

    public function detail(): JsonResponse
    {
        $name = LanguageHelper::getName();
        return Response::success('Authenticated user', [
            "user" => Auth::user()->load([
                'authority' => function ($query) use ($name) {
                    $query->select(['id', $name . ' as name','*']);
                }
            ])
        ]);
    }
}
