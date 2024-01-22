<?php

namespace App\Exceptions\CustomValidationException;

use Exception;

class CustomValidationException extends Exception
{
    public function __construct($message = "Unprocessable entity", $code = 422)
    {
        parent::__construct($message, $code);
    }
}
