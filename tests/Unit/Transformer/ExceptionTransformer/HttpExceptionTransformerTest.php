<?php

declare(strict_types=1);

namespace Weblabel\ApiBundle\Tests\Unit\Transformer\ExceptionTransformer;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\GoneHttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\LengthRequiredHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\PreconditionFailedHttpException;
use Symfony\Component\HttpKernel\Exception\PreconditionRequiredHttpException;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\HttpKernel\Exception\UnsupportedMediaTypeHttpException;
use Weblabel\ApiBundle\Exception\Exception;
use Weblabel\ApiBundle\Transformer\ExceptionTransformer\HttpExceptionTransformer;

class HttpExceptionTransformerTest extends TestCase
{
    private $transformer;

    public function test_that_transformer_supports_http_exceptions()
    {
        self::assertTrue($this->transformer->supports(new BadRequestHttpException()));
    }

    public function test_that_transformer_doesnt_support_non_http_exceptions()
    {
        self::assertFalse($this->transformer->supports(new Exception()));
    }

    /**
     * @dataProvider httpExceptionProvider
     */
    public function test_exception_transforming(HttpExceptionInterface $exception)
    {
        self::assertSame(
            [
                'status' => $exception->getStatusCode(),
                'title' => $exception->getMessage(),
                'type' => 'about:blank',
            ],
            $this->transformer->transform($exception)->toArray()
        );
    }

    public function httpExceptionProvider()
    {
        return [
            [new AccessDeniedHttpException('foo')],
            [new BadRequestHttpException('foo')],
            [new ConflictHttpException('foo')],
            [new GoneHttpException('foo')],
            [new LengthRequiredHttpException('foo')],
            [new MethodNotAllowedHttpException([], 'foo')],
            [new NotAcceptableHttpException('foo')],
            [new NotFoundHttpException('foo')],
            [new PreconditionFailedHttpException('foo')],
            [new PreconditionRequiredHttpException('foo')],
            [new ServiceUnavailableHttpException(1, 'foo')],
            [new TooManyRequestsHttpException(1, 'foo')],
            [new UnauthorizedHttpException('foo', 'foo')],
            [new UnprocessableEntityHttpException('foo')],
            [new UnsupportedMediaTypeHttpException('foo')],
        ];
    }

    protected function setUp() : void
    {
        $this->transformer = new HttpExceptionTransformer();
    }
}
