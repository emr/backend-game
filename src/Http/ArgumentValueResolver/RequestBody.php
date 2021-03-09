<?php

namespace App\Http\ArgumentValueResolver;

use Attribute;
use Symfony\Component\HttpKernel\Attribute\ArgumentInterface;

/**
 * Marks that the argument has a typed request.
 *
 * @see RequestBodyResolver
 */
#[Attribute(Attribute::TARGET_PARAMETER)]
class RequestBody implements ArgumentInterface
{
}
