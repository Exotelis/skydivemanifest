<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Validation\ValidationException;
use Laravel\Passport\Exceptions\MissingScopeException;
use Laravel\Passport\Exceptions\OAuthServerException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

/**
 * Class Handler
 * @package App\Exceptions
 */
class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        MissingScopeException::class,
        ModelNotFoundException::class,
        NotFoundHttpException::class,
        ValidationException::class,
        OAuthServerException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($request->ajax()) {
            if ($exception instanceof MissingScopeException) {
                return response()->json([
                    'message' => __('error.access_denied'),
                ], 403);
            }

            if ($exception instanceof ValidationException) {
                return response()->json([
                    'message' => __('error.validation_error'),
                    'errors' => $exception->validator->getMessageBag()
                ], 422);
            }
        }

        if ($exception instanceof ThrottleRequestsException) {
            $exception = new ThrottleRequestsException(__('error.too_many_attempts'), $exception);
        }

        return parent::render($request, $exception);
    }
}
