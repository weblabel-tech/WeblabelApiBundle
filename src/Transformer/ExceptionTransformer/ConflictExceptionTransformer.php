<?php

declare(strict_types=1);

namespace Weblabel\ApiBundle\Transformer\ExceptionTransformer;

use Weblabel\ApiBundle\Exception\ConflictException;
use Weblabel\ApiBundle\Transformer\ExceptionTransformerInterface;
use Weblabel\ApiBundle\Transformer\ExceptionWrapper;

final class ConflictExceptionTransformer implements ExceptionTransformerInterface
{
    /**
     * Transforms ConflictException object into exception wrapper object.
     *
     * @param ConflictException $throwable
     */
    public function transform(\Throwable $throwable): ExceptionWrapper
    {
        return new ExceptionWrapper(
            $throwable->getStatusCode(),
            $throwable->getMessage(),
            'conflict',
            [
                'errors' => $throwable->getErrors(),
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function supports(\Throwable $throwable): bool
    {
        return $throwable instanceof ConflictException;
    }
}
