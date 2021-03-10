<?php

namespace App\Http\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @see UniquePlayersValidator
 */
#[\Attribute]
class UniquePlayers extends Constraint
{
    public function __construct(public string $message)
    {
        parent::__construct();
    }
}
