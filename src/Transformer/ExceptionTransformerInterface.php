<?php

declare(strict_types=1);

namespace Weblabel\ApiBundle\Transformer;

interface ExceptionTransformerInterface
{
    /**
     * Transforms an exception object into exception wrapper object.
     */
    public function transform(\Throwable $throwable): ExceptionWrapper;

    /**
     * Checks if given exception is supported.
     */
    public function supports(\Throwable $throwable): bool;
}
