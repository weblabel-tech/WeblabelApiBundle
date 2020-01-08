<?php

declare(strict_types=1);

namespace Weblabel\ApiBundle\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Weblabel\ApiBundle\Transformer\ExceptionTransformerResolverInterface;

final class ExceptionListener
{
    /** @var ExceptionTransformerResolverInterface */
    private $exceptionTransformerResolver;

    public function __construct(ExceptionTransformerResolverInterface $exceptionTransformerResolver)
    {
        $this->exceptionTransformerResolver = $exceptionTransformerResolver;
    }

    public function onKernelException(ExceptionEvent $exceptionEvent) : void
    {
        $throwable = $exceptionEvent->getThrowable();
        $wrapper = $this->exceptionTransformerResolver->resolve($throwable)->transform($throwable);

        $response = new JsonResponse($wrapper->toArray(), $wrapper->getStatusCode(), ['Content-Type' => 'application/problem+json']);

        $exceptionEvent->setResponse($response);
    }
}
