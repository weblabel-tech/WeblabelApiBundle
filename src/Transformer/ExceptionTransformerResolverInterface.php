<?php

declare(strict_types=1);

namespace Weblabel\ApiBundle\Transformer;

use Weblabel\ApiBundle\Exception\LogicException;

interface ExceptionTransformerResolverInterface
{
    /**
     * Returns transformer for given exception.
     *
     * @throws LogicException
     */
    public function resolve(\Throwable $throwable): ExceptionTransformerInterface;
}
