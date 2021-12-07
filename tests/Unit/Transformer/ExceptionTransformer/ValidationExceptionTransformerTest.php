<?php

declare(strict_types=1);

namespace Weblabel\ApiBundle\Tests\Unit\Transformer\ExceptionTransformer;

use PHPUnit\Framework\TestCase;
use Weblabel\ApiBundle\Exception\Exception;
use Weblabel\ApiBundle\Exception\ValidationException;
use Weblabel\ApiBundle\Transformer\ExceptionTransformer\ValidationExceptionTransformer;

class ValidationExceptionTransformerTest extends TestCase
{
    private $transformer;

    public function testThatTransformerSupportsValidationException()
    {
        self::assertTrue($this->transformer->supports(new ValidationException([])));
    }

    public function testThatTransformerDoesntSupportNonValidationExceptions()
    {
        self::assertFalse($this->transformer->supports(new Exception()));
    }

    public function testExceptionTransforming()
    {
        $exception = new ValidationException(['foo' => 'bar']);

        self::assertSame(
            [
                'status' => $exception->getStatusCode(),
                'title' => $exception->getMessage(),
                'type' => 'validation_error',
                'errors' => [
                    'foo' => 'bar',
                ],
            ],
            $this->transformer->transform($exception)->toArray()
        );
    }

    protected function setUp(): void
    {
        $this->transformer = new ValidationExceptionTransformer();
    }
}
