<?php

namespace iflow\native\Exceptions;

class RegisterServiceValidException extends \Exception {

    public function __construct(string $message = 'Register Boot Service Valid Exception', int $code = 0, ?\Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }

}