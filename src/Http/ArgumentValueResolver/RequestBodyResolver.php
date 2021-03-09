<?php

namespace App\Http\ArgumentValueResolver;

use App\Http\Exception\ValidationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\Exception\RuntimeException as SerializerRuntimeException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Resolves values of arguments that marked with `App\Http\ArgumentValueResolver\RequestBody` attribute by their typehint.
 * If the typed class cannot be instantiated, throws error.
 * By default, validates the request object and throws if it fails.
 *
 * Example:
 * ```
 *     ...
 *     public function myRequest(#[RequestBody] MyRequestShape $myRequest)
 *     ...
 * ```
 */
class RequestBodyResolver implements ArgumentValueResolverInterface
{
    public function __construct(private ValidatorInterface $validator, private SerializerInterface $serializer)
    {
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return
            $argument->getAttribute() instanceof RequestBody &&
            null !== $argument->getType() &&
            class_exists($argument->getType());
    }

    /**
     * @return iterable<object>
     *
     * @throws SerializerRuntimeException When the request body object could not be instantiated
     * @throws BadRequestHttpException    When the request body is not valid
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $data = $request->getContent();
        $class = $argument->getType();

        if (null === $class) {
            return [];
        }

        try {
            $body = $this->serializer->deserialize($data, $class, 'json');
        } catch (NotEncodableValueException) {
            throw new BadRequestHttpException('Invalid request body');
        } catch (NotNormalizableValueException) {
            throw new BadRequestHttpException('Request schema is invalid');
        }

        $errors = $this->validator->validate($body);

        if (\count($errors) > 0) {
            throw new ValidationException($errors->get(0)->getMessage());
        }

        yield $body;
    }
}
