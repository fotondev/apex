<?php

namespace App\Exceptions;

use Throwable;

class CurlException extends \RuntimeException
{
    public function __construct(
        public readonly array $errors,
        string $message = "cURL Error: ",
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $previous);
    }
}