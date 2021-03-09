<?php

namespace App\Http\Serializer;

use App\Http\Schema\Response\ErrorResponse;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

/**
 * Converts application exceptions to an `ErrorResponse` shaped error
 * and adds normalization of exception objects support to Serializer.
 */
class RequestErrorNormalizer implements NormalizerInterface
{
    public function __construct(private ObjectNormalizer $objectNormalizer)
    {
    }

    /**
     * @param FlattenException $exception
     * @param mixed[]          $context
     */
    public function normalize($exception, string $format = null, array $context = [])
    {
        return $this->objectNormalizer->normalize(
            new ErrorResponse($exception->getMessage(), $exception->getStatusCode())
        );
    }

    public function supportsNormalization($data, string $format = null): bool
    {
        return $data instanceof FlattenException;
    }
}
