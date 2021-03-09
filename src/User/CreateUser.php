<?php

namespace App\User;

use Symfony\Component\Validator\Constraints as Assert;

class CreateUser
{
    #[Assert\NotBlank(message: 'Username should not be empty')]
    public string $username;

    #[Assert\NotBlank(message: 'Password should not be empty')]
    public string $password;

    public function __construct(string $username = '', string $password = '')
    {
        $this->username = $username;
        $this->password = $password;
    }
}
