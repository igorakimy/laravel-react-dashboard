<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Spatie\Permission\Exceptions\UnauthorizedException;
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

        $this->renderable(function (UnauthorizedException $e, $request) {
            return response()->json([
                'message' => 'Access denied',
            ], 403);
        });

        $this->renderable(function (AppException $e, Request $request) {
            $code = $e->getExceptionCode();

            return response()->json([
                'status' => 'error',
                'code' => $code->value,
                'message' => $e->getMessage(),
            ], $e->getCode());
        });
    }
}
