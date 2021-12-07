<?php

declare(strict_types=1);

namespace Weblabel\ApiBundle\Tests\Unit\Transformer;

use PHPUnit\Framework\TestCase;
use Weblabel\ApiBundle\Exception\Exception;
use Weblabel\ApiBundle\Exception\LogicException;
use Weblabel\ApiBundle\Transformer\ExceptionTransformerInterface;
use Weblabel\ApiBundle\Transformer\ExceptionTransformerResolver;

class ExceptionTransformerResolverTest extends TestCase
{
    public function testTransformerResolvingForUnsupportedException()
    {
        $this->expectException(LogicException::class);

        $resolver = new ExceptionTransformerResolver([]);
        $resolver->resolve(new \Exception());
    }

    public function testTransformerResolving()
    {
        $exception = new Exception();
        $validationExceptionTransformer = $this->createMock(ExceptionTransformerInterface::class);
        $validationExceptionTransformer
            ->expects(self::once())
            ->method('supports')
            ->with($exception)
            ->willReturn(false);

        $genericExceptionTransformer = $this->createMock(ExceptionTransformerInterface::class);
        $genericExceptionTransformer
            ->expects(self::once())
            ->method('supports')
            ->with($exception)
            ->willReturn(true);

        $resolver = new ExceptionTransformerResolver([$validationExceptionTransformer, $genericExceptionTransformer]);
        $transformer = $resolver->resolve($exception);

        self::assertSame($transformer, $genericExceptionTransformer);
    }
}
