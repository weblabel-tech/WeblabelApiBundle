<?php

declare(strict_types=1);

namespace Weblabel\ApiBundle\Tests\Unit\Transformer\ExceptionTransformer;

use PHPUnit\Framework\TestCase;
use Weblabel\ApiBundle\Transformer\ExceptionTransformer\GenericExceptionTransformer;

class GenericExceptionTransformerTest extends TestCase
{
    public function testThatTransformerSupportsAllExceptions()
    {
        $transformer = new GenericExceptionTransformer(false);

        self::assertTrue($transformer->supports(new \Exception('foo')));
    }

    public function testExceptionTransformingWithDisabledDebug()
    {
        $transformer = new GenericExceptionTransformer(false);

        self::assertSame(
            [
                'status' => 500,
                'title' => 'Application error',
                'type' => 'about:blank',
            ],
            $transformer->transform(new \Exception('foo'))->toArray()
        );
    }

    public function testExceptionTransformingWithEnabledDebug()
    {
        $transformer = new GenericExceptionTransformer(true);
        $transformedResponse = $transformer->transform(new \Exception('foo'))->toArray();

        self::assertArrayHasKey('title', $transformedResponse);
        self::assertArrayHasKey('status', $transformedResponse);
        self::assertArrayHasKey('type', $transformedResponse);
        self::assertArrayHasKey('details', $transformedResponse);

        self::assertSame('foo', $transformedResponse['title']);
        self::assertSame(500, $transformedResponse['status']);
        self::assertSame('about:blank', $transformedResponse['type']);

        self::assertArrayHasKey('code', $transformedResponse['details']);
        self::assertSame(0, $transformedResponse['details']['code']);
        self::assertArrayHasKey('file', $transformedResponse['details']);
        self::assertSame(__FILE__, $transformedResponse['details']['file']);
        self::assertArrayHasKey('line', $transformedResponse['details']);
        self::assertIsInt($transformedResponse['details']['line']);
        self::assertArrayHasKey('trace', $transformedResponse['details']);
        self::assertIsArray($transformedResponse['details']['trace']);
    }

    public function testExceptionTransformingForEmptyExceptionMessage()
    {
        $transformer = new GenericExceptionTransformer(true);
        $transformedResponse = $transformer->transform(new \Exception())->toArray();

        self::assertArrayHasKey('title', $transformedResponse);
        self::assertSame('Application error', $transformedResponse['title']);
    }
}
