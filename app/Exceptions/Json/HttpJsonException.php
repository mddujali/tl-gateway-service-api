<?php

declare(strict_types=1);

namespace App\Exceptions\Json;

use Illuminate\Http\Response;
use JsonException;

class HttpJsonException extends JsonException
{
    protected int $status;

    protected string $errorCode;

    public function __construct(int $status, string $errorCode = '', string $message = '', protected array $errors = [])
    {
        parent::__construct($message);

        $statusText = Response::$statusTexts[$status];

        $this->status = $status;
        $this->errorCode = ! blank($errorCode) ? studly($errorCode) : studly($statusText);
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getErrorCode(): string
    {
        return $this->errorCode;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
