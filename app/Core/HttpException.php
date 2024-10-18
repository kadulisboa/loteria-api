<?php


namespace App\Core;

use Exception;

class HttpException extends Exception
{
    private $statusCode;

    public function __construct($statusCode = 500, $message = "Internal Server Error")
    {
        parent::__construct($message);
        $this->statusCode = $statusCode;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }
}
