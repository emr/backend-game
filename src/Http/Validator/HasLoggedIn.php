<?php

namespace App\Http\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @see HasLoggedInValidator
 */
#[\Attribute]
class HasLoggedIn extends Constraint
{
    public function __construct(public string $message)
    {
        parent::__construct();
    }
}
