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

    public function test_that_transformer_supports_validation_exception()
    {
        self::assertTrue($this->transformer->supports(new ValidationException([])));
    }

    public function test_that_transformer_doesnt_support_non_validation_exceptions()
    {
        self::assertFalse($this->transformer->supports(new Exception()));
    }

    public function test_exception_transforming()
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
