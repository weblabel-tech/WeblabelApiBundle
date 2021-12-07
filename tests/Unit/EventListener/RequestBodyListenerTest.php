<?php

declare(strict_types=1);

namespace Weblabel\ApiBundle\Tests\Unit\EventListener;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnsupportedMediaTypeHttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Weblabel\ApiBundle\EventListener\RequestBodyListener;
use Weblabel\DataTransformer\DecoderInterface;
use Weblabel\DataTransformer\DecoderResolverInterface;
use Weblabel\DataTransformer\Exception\InvalidPayloadException;
use Weblabel\DataTransformer\Exception\UnsupportedFormatException;

class RequestBodyListenerTest extends TestCase
{
    /**
     * @dataProvider payloadLessMethodProvider
     */
    public function testDecodingForPayloadlessMethod(string $method)
    {
        $decoderResolver = $this->createMock(DecoderResolverInterface::class);
        $decoderResolver
            ->expects($this->never())
            ->method('resolve');

        $requestEvent = new RequestEvent($this->createMock(HttpKernelInterface::class), $this->getRequest($method, '{"foo":"bar"}'), null);
        $requestBodyListener = new RequestBodyListener($decoderResolver);
        $requestBodyListener->onKernelRequest($requestEvent);
    }

    /**
     * @dataProvider emptyPayloadProvider
     */
    public function testDecodingWithEmptyPayload($payload)
    {
        $decoderResolver = $this->createMock(DecoderResolverInterface::class);
        $decoderResolver
            ->expects($this->never())
            ->method('resolve');

        $requestEvent = new RequestEvent($this->createMock(HttpKernelInterface::class), $this->getRequest('POST', $payload), null);
        $requestBodyListener = new RequestBodyListener($decoderResolver);
        $requestBodyListener->onKernelRequest($requestEvent);
    }

    /**
     * @dataProvider payloadAwareMethodProvider
     */
    public function testDecodingPayload(string $method)
    {
        $jsonDecoder = $this->createMock(DecoderInterface::class);
        $jsonDecoder
            ->expects(self::once())
            ->method('decode')
            ->with('{"foo":"bar"}')
            ->willReturn(['foo' => 'bar']);

        $decoderResolver = $this->createMock(DecoderResolverInterface::class);
        $decoderResolver
            ->expects($this->once())
            ->method('resolve')
            ->willReturn($jsonDecoder);

        $request = $this->getRequest($method, '{"foo":"bar"}');
        $requestEvent = new RequestEvent($this->createMock(HttpKernelInterface::class), $request, null);
        $requestBodyListener = new RequestBodyListener($decoderResolver);
        $requestBodyListener->onKernelRequest($requestEvent);

        self::assertSame(['foo' => 'bar'], $request->request->all());
    }

    public function testDecodingWithInvalidPayload()
    {
        $this->expectException(BadRequestHttpException::class);
        $jsonDecoder = $this->createMock(DecoderInterface::class);
        $jsonDecoder
            ->expects(self::once())
            ->method('decode')
            ->with('{"foo":"bar"]')
            ->willThrowException(new InvalidPayloadException());

        $decoderResolver = $this->createMock(DecoderResolverInterface::class);
        $decoderResolver
            ->expects($this->once())
            ->method('resolve')
            ->willReturn($jsonDecoder);

        $request = $this->getRequest('POST', '{"foo":"bar"]');
        $requestEvent = new RequestEvent($this->createMock(HttpKernelInterface::class), $request, null);
        $requestBodyListener = new RequestBodyListener($decoderResolver);
        $requestBodyListener->onKernelRequest($requestEvent);
    }

    public function testDecodingForUnsupportedFormat()
    {
        $this->expectException(UnsupportedMediaTypeHttpException::class);
        $decoderResolver = $this->createMock(DecoderResolverInterface::class);
        $decoderResolver
            ->expects($this->once())
            ->method('resolve')
            ->with('xml')
            ->willThrowException(new UnsupportedFormatException());

        $request = $this->getRequest('POST', '<foo/>', 'application/xml');
        $requestEvent = new RequestEvent($this->createMock(HttpKernelInterface::class), $request, null);
        $requestBodyListener = new RequestBodyListener($decoderResolver);
        $requestBodyListener->onKernelRequest($requestEvent);
    }

    public function payloadAwareMethodProvider(): array
    {
        return [
            ['POST'],
            ['PUT'],
            ['PATCH'],
            ['DELETE'],
        ];
    }

    public function payloadLessMethodProvider(): array
    {
        return [
            ['GET'],
            ['HEAD'],
            ['OPTIONS'],
        ];
    }

    public function emptyPayloadProvider()
    {
        return [
            [null],
            [''],
        ];
    }

    private function getRequest(string $method, string $content = null, string $contentType = 'application/json'): Request
    {
        return Request::create('https://example.com', $method, [], [], [], ['CONTENT_TYPE' => $contentType], $content);
    }
}
