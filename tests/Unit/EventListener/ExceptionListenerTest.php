<?php

declare(strict_types=1);

namespace Weblabel\ApiBundle\Tests\Unit\EventListener;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Weblabel\ApiBundle\EventListener\ExceptionListener;
use Weblabel\ApiBundle\Transformer\ExceptionTransformerInterface;
use Weblabel\ApiBundle\Transformer\ExceptionTransformerResolverInterface;
use Weblabel\ApiBundle\Transformer\ExceptionWrapper;

class ExceptionListenerTest extends TestCase
{
    public function test_exception_handling()
    {
        $exception = new \Exception();
        $transformer = $this->createMock(ExceptionTransformerInterface::class);
        $transformer
            ->expects($this->once())
            ->method('transform')
            ->with($exception)
            ->willReturn(new ExceptionWrapper(400));

        $transformerResolver = $this->createMock(ExceptionTransformerResolverInterface::class);
        $transformerResolver
            ->expects($this->once())
            ->method('resolve')
            ->with($exception)
            ->willReturn($transformer);

        $exceptionEvent = new ExceptionEvent($this->createMock(HttpKernelInterface::class), new Request(), HttpKernelInterface::MASTER_REQUEST, $exception);
        $requestBodyListener = new ExceptionListener($transformerResolver);
        $requestBodyListener->onKernelException($exceptionEvent);

        self::assertSame(400, $exceptionEvent->getResponse()->getStatusCode());
        self::assertSame('application/problem+json', $exceptionEvent->getResponse()->headers->get('Content-Type'));
        self::assertJsonStringEqualsJsonString('{"status":400,"title":"Application error","type":"about:blank"}', $exceptionEvent->getResponse()->getContent());
    }
}
