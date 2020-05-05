<?php

declare(strict_types=1);

namespace Weblabel\ApiBundle\Tests\Unit\Transformer\ExceptionTransformer;

use PHPUnit\Framework\TestCase;
use Weblabel\ApiBundle\Exception\ConflictException;
use Weblabel\ApiBundle\Exception\Exception;
use Weblabel\ApiBundle\Transformer\ExceptionTransformer\ConflictExceptionTransformer;

class ConflictExceptionTransformerTest extends TestCase
{
    private $transformer;

    public function test_that_transformer_supports_conflict_exception()
    {
        self::assertTrue($this->transformer->supports(new ConflictException([])));
    }

    public function test_that_transformer_doesnt_support_non_conflict_exceptions()
    {
        self::assertFalse($this->transformer->supports(new Exception()));
    }

    public function test_exception_transforming()
    {
        $exception = new ConflictException(['foo' => 'bar']);

        self::assertSame(
            [
                'status' => $exception->getStatusCode(),
                'title' => $exception->getMessage(),
                'type' => 'conflict',
                'errors' => [
                    'foo' => 'bar',
                ],
            ],
            $this->transformer->transform($exception)->toArray()
        );
    }

    protected function setUp(): void
    {
        $this->transformer = new ConflictExceptionTransformer();
    }
}
