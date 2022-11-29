<?php

namespace Core\Database\Mysql;

use Core\Facades\Log;

class DbException extends \Exception
{
    public function __construct($message = "", $code = 1, $previous = null)
    {
        Log::error($message, 3 + $code);
        parent::__construct($message, $code, $previous);
    }
}