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

    public function testThatTransformerSupportsConflictException()
    {
        self::assertTrue($this->transformer->supports(new ConflictException([])));
    }

    public function testThatTransformerDoesntSupportNonConflictExceptions()
    {
        self::assertFalse($this->transformer->supports(new Exception()));
    }

    public function testExceptionTransforming()
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
