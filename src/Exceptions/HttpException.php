<?php

namespace Core\Exceptions;

use One\Facades\Log;
use One\Http\Response;

class HttpException extends \Exception
{
    /**
     * @var Response
     */
    public $response = null;

    public function __construct(Response $response, $message = "", $code = 0, $previous = null)
    {
        $this->response = $response;
        parent::__construct($message, $code);
    }
}