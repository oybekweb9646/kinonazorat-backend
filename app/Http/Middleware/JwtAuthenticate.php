<?php

namespace App\Http\Middleware;

use App\Core\Enums\User\UserStatusEnum;
use Closure;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpFoundation\Response;

class JwtAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'user not found'
                ], 401);
            }

            if ($user->status == UserStatusEnum::_BANNED) {
                return response()->json([
                    'success' => false,
                    'message' => 'user banned'
                ], 401);
            }
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 401);
        }

        return $next($request);
    }
}
