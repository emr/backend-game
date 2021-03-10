<?php

namespace App\Http\Validator;

use App\User\LoginHandler;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

/**
 * Checks if the user with the provided id string has logged in.
 */
class HasLoggedInValidator extends ConstraintValidator
{
    public function __construct(private LoginHandler $loginHandler)
    {
    }

    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof HasLoggedIn) {
            throw new UnexpectedTypeException($constraint, HasLoggedIn::class);
        }

        if (empty($value)) {
            return;
        }

        if (!\is_int($value)) {
            throw new UnexpectedValueException($value, 'integer');
        }

        if (!$this->loginHandler->hasLoggedIn($value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ id }}', (string) $value)
                ->addViolation();
        }
    }
}
