<?php

namespace App\Http\Schema\Response;

class ErrorResponse
{
    public function __construct(public string $message, public int $code)
    {
    }
}
