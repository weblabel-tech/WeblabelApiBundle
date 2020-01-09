<?php

declare(strict_types=1);

namespace Weblabel\ApiBundle\Transformer\ExceptionTransformer;

use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Weblabel\ApiBundle\Transformer\ExceptionTransformerInterface;
use Weblabel\ApiBundle\Transformer\ExceptionWrapper;

final class HttpExceptionTransformer implements ExceptionTransformerInterface
{
    /**
     * Transforms HttpException object into exception wrapper object.
     *
     * @param HttpExceptionInterface $throwable
     */
    public function transform(\Throwable $throwable): ExceptionWrapper
    {
        return new ExceptionWrapper($throwable->getStatusCode(), $throwable->getMessage());
    }

    /**
     * {@inheritdoc}
     */
    public function supports(\Throwable $throwable): bool
    {
        return $throwable instanceof HttpExceptionInterface;
    }
}
