<?php

namespace App\Exceptions;

use App\Models\ErrorLog;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            if ($e instanceof ValidationException) {
                return;
            }

            try {
                /** @var Request $request */
                $request = request();
                $user = auth('api')->user();

                ErrorLog::create([
                    'user_id'         => $user?->id,
                    'username'        => $user?->username ?? $user?->full_name,
                    'user_role'       => $user?->role,
                    'method'          => $request?->method(),
                    'url'             => $request?->path(),
                    'message'         => $e->getMessage(),
                    'exception_class' => get_class($e),
                    'file'            => $e->getFile(),
                    'line'            => $e->getLine(),
                    'trace'           => substr($e->getTraceAsString(), 0, 3000),
                    'ip_address'      => $request?->ip(),
                    'status_code'     => method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500,
                ]);
            } catch (\Throwable) {
                // Error log yozishda xatolik bo'lsa tizim to'xtamasin
            }
        });
    }
}
