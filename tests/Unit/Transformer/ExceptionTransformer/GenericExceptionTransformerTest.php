<?php

declare(strict_types=1);

namespace Weblabel\ApiBundle\Tests\Unit\Transformer\ExceptionTransformer;

use PHPUnit\Framework\TestCase;
use Weblabel\ApiBundle\Transformer\ExceptionTransformer\GenericExceptionTransformer;

class GenericExceptionTransformerTest extends TestCase
{
    public function test_that_transformer_supports_all_exceptions()
    {
        $transformer = new GenericExceptionTransformer(false);

        self::assertTrue($transformer->supports(new \Exception('foo')));
    }

    public function test_exception_transforming_with_disabled_debug()
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

    public function test_exception_transforming_with_enabled_debug()
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

    public function test_exception_transforming_for_empty_exception_message()
    {
        $transformer = new GenericExceptionTransformer(true);
        $transformedResponse = $transformer->transform(new \Exception())->toArray();

        self::assertArrayHasKey('title', $transformedResponse);
        self::assertSame('Application error', $transformedResponse['title']);
    }
}
