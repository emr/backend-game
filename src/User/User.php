<?php

namespace App\User;

use Symfony\Component\Serializer\Annotation\Groups;

class User
{
    #[Groups(['signUp', 'signIn'])]
    public string | null $id;

    #[Groups(['signUp', 'signIn'])]
    public string $username;

    #[Groups(['signUp'])]
    public string $password;

    public function __construct(string $username, string $password)
    {
        $this->username = $username;
        $this->password = $password;
    }
}
