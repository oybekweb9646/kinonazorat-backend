<?php
use App\Core\Enums\Routes\RoutePrefixEnum;
use App\Exceptions\ForbiddenAccessException;
use App\Http\Middleware\JwtAuthenticate;
use App\Http\Middleware\SetUpLanguageMiddleware;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // $middleware->web([]);

        $middleware->api([
            SetUpLanguageMiddleware::class,
        ]);

        $middleware->alias(['jwt.verify' => JwtAuthenticate::class]);

    })
    ->withExceptions(function (Exceptions $exceptions) {

        $exceptions->render(function (Throwable $e, $request) {

            $statusCode = 500;
            $contentType = $request->header('content-type');
            $routePrefix = Route::current()?->getPrefix();

            if (
                $contentType === 'application/json'
                || (!empty($routePrefix) && in_array($routePrefix, RoutePrefixEnum::getList()))
            ) {

                if (!($e instanceof ValidationException)) {

                    $message = $e->getMessage();

                    if ($e instanceof ModelNotFoundException) {
                        $statusCode = ResponseAlias::HTTP_NOT_FOUND;

                        try {
                            if (!empty($e->getModel())) {
                                $models = explode('\\', $e->getModel());
                                if (is_array($models)) {
                                    $message = implode(' ', preg_split('/(?<=\\w)(?=[A-Z])/', $models[count($models) - 1])) . ' is not found';
                                }
                            }
                        } catch (\Exception $e) {
                            $message = __('client.Data is not found');
                        }
                    }

                    if ($e instanceof QueryException && config('app.env') === 'production') {
                        Log::error($e);
                        $message = __('client.Query exception');
                    }

                    if ($e instanceof UnauthorizedHttpException) {
                        $statusCode = ResponseAlias::HTTP_UNAUTHORIZED;
                    }

                    if ($e instanceof ForbiddenAccessException) {
                        $statusCode = ResponseAlias::HTTP_FORBIDDEN;
                    }

                    if ($e instanceof BadRequestHttpException) {
                        $statusCode = ResponseAlias::HTTP_BAD_REQUEST;
                    }

                    return response()->json([
                        'status'  => false,
                        'message' => $message,
                        'code'    => $e->getCode(),
                        'data'    => [],
                        'trace'   => App::hasDebugModeEnabled() ? $e->getTrace() : [],
                    ], $statusCode);
                }
            }

            if ($e instanceof AuthorizationException) {
                $statusCode = ResponseAlias::HTTP_UNAUTHORIZED;
            }

            if ($e instanceof UnauthorizedHttpException) {
                $statusCode = ResponseAlias::HTTP_UNAUTHORIZED;
            }

            return response()->json([
                'status'  => false,
                'message' => $e->getMessage(),
                'code'    => $e->getCode(),
                'data'    => [],
                'trace'   => App::hasDebugModeEnabled() ? $e->getTrace() : [],
            ], $statusCode);
        });

    })->create();
