<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        $exceptionCode = $exception->getCode() >= 100 && $exception->getCode() < 600 ? $exception->getCode() : 500;
        if ($exception instanceof ValidationException) {
            $exceptionCode = 422;
        }
        if ($exception instanceof NotFoundHttpException) {
            $exceptionCode = 404;
        }
        return response()->json(['message' => $exception->getMessage()], $exceptionCode);
    }
}
