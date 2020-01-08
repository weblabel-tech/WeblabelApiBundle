<?php

declare(strict_types=1);

namespace Weblabel\ApiBundle\Transformer\ExceptionTransformer;

use Weblabel\ApiBundle\Exception\ValidationException;
use Weblabel\ApiBundle\Transformer\ExceptionTransformerInterface;
use Weblabel\ApiBundle\Transformer\ExceptionWrapper;

final class ValidationExceptionTransformer implements ExceptionTransformerInterface
{
    /**
     * Transforms ValidationException object into exception wrapper object.
     *
     * @param ValidationException $throwable
     */
    public function transform(\Throwable $throwable) : ExceptionWrapper
    {
        return new ExceptionWrapper(
            $throwable->getStatusCode(),
            $throwable->getMessage(),
            'validation_error',
            [
                'errors' => $throwable->getErrors(),
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function supports(\Throwable $throwable) : bool
    {
        return $throwable instanceof ValidationException;
    }
}
