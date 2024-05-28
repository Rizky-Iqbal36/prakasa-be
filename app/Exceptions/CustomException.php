<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class CustomHTTPException extends Exception
{
    protected $flag;
    protected $message;
    protected $statusCode;
    protected $details;

    public function __construct($flag = "INTERNAL_SERVER_ERROR", $message = "Error occurred", $details, $statusCode)
    {
        parent::__construct($flag);

        $this->message = $message;
        $this->statusCode = $statusCode;
        $this->details = $details;
    }

    public function render(): JsonResponse
    {
        $response = [
            'status_code' => $this->statusCode,
            'success' => false,
            'message' => $this->message,
        ];
        if (!empty($this->details))
            $response['details'] = $this->details;

        return response()->json($response, $this->statusCode);
    }
}


class BadRequest extends CustomHTTPException
{
    public function __construct($message = "Bad Request", $details = [])
    {
        parent::__construct("BAD_REQUEST", $message, $details, 400);
    }
}

class Unauthorized extends CustomHttpException
{
    public function __construct($message = "Unauthorized", $details = [])
    {
        parent::__construct("UNAUTHORIZED", $message, $details, 401);
    }
}


class NotFound extends CustomHTTPException
{
    public function __construct($message = "Not Found", $details = [])
    {
        parent::__construct("NOT_FOUND", $message, $details, 404);
    }
}
