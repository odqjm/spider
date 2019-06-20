<?php

declare(strict_types=1);

namespace Component;

use Exception;
use Throwable;

class SmsException extends Exception
{
    public function __construct($message, $code, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
