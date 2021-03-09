<?php

namespace App\Http\Schema\Request;

class SignInRequest
{
    public function __construct(public string $username = '', public string $password = '')
    {
    }
}
