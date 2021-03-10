<?php

namespace App\Http\Validator;

use App\Rank\PlayerScore;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

/**
 * Checks if any player has scores more than 1.
 */
class UniquePlayersValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof UniquePlayers) {
            throw new UnexpectedTypeException($constraint, UniquePlayers::class);
        }

        if (empty($value)) {
            return;
        }

        if (!\is_array($value)) {
            throw new UnexpectedValueException($value, 'array<\App\Rank\PlayerScore>');
        }

        $ids = [];
        foreach ($value as $playerScore) {
            if (!$playerScore instanceof PlayerScore) {
                throw new UnexpectedValueException($playerScore, PlayerScore::class);
            }

            if (true === ($ids[$playerScore->id] ?? null)) {
                $this->context->buildViolation($constraint->message)
                    ->setParameter('{{ id }}', (string) $playerScore->id)
                    ->addViolation();
                $ids[$playerScore->id] = false;
            } else {
                $ids[$playerScore->id] = true;
            }
        }
    }
}
