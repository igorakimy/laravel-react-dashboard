<?php

namespace App\Exceptions;

use App\Enums\ExceptionCode;
use Exception;

class AppException extends Exception
{
    /**
     * @var ExceptionCode
     */
    private ExceptionCode $exceptionCode;

    /**
     * Create new app exception instance.
     *
     * @param  ExceptionCode  $code
     * @param  string|null  $message
     * @param  int|null  $statusCode
     *
     * @return static
     */
    public static function new(
        ExceptionCode $code,
        ?string $message = null,
        ?int $statusCode = null,
    ): static {
        $exception = new static(
            $message ?? $code->getMessage(),
            $statusCode ?? $code->getStatusCode()
        );

        $exception->exceptionCode = $code;

        return $exception;
    }

    /**
     * Get exception code.
     *
     * @return ExceptionCode
     */
    public function getExceptionCode(): ExceptionCode
    {
        return $this->exceptionCode;
    }
}
