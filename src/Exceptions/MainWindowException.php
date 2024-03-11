<?php

namespace iflow\native\Exceptions;

class MainWindowException extends \Exception {

    public function __construct(string $message = '请设置主窗口配置信息', int $code = 0, ?\Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }

}