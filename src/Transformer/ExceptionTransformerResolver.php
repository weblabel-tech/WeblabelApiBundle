<?php

declare(strict_types=1);

namespace Weblabel\ApiBundle\Transformer;

use Weblabel\ApiBundle\Exception\LogicException;

final class ExceptionTransformerResolver implements ExceptionTransformerResolverInterface
{
    /** @var ExceptionTransformerInterface[] */
    private array $transformers = [];

    /**
     * @param iterable|ExceptionTransformerInterface[] $transformers
     */
    public function __construct(iterable $transformers)
    {
        foreach ($transformers as $transformer) {
            $this->addTransformer($transformer);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(\Throwable $throwable): ExceptionTransformerInterface
    {
        foreach ($this->transformers as $transformer) {
            if ($transformer->supports($throwable)) {
                return $transformer;
            }
        }

        throw new LogicException(\sprintf('Cannot find transformer for given exception "%s"', \get_class($throwable)));
    }

    /**
     * Adds new exception transformer.
     */
    public function addTransformer(ExceptionTransformerInterface $transformer): void
    {
        $this->transformers[] = $transformer;
    }
}
