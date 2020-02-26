<?php

declare(strict_types=1);

namespace Weblabel\ApiBundle\EventListener;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnsupportedMediaTypeHttpException;
use Weblabel\DataTransformer\DecoderResolverInterface;
use Weblabel\DataTransformer\Exception\InvalidPayloadException;
use Weblabel\DataTransformer\Exception\UnsupportedFormatException;

final class RequestBodyListener
{
    /** @var array */
    private static array $methodsWithoutPayload = [
        'GET',
        'HEAD',
        'OPTIONS',
    ];

    /** @var DecoderResolverInterface */
    private DecoderResolverInterface $decoderResolver;

    public function __construct(DecoderResolverInterface $decoderResolver)
    {
        $this->decoderResolver = $decoderResolver;
    }

    /**
     * Handles request payload decoding.
     *
     * @throws UnsupportedMediaTypeHttpException
     * @throws BadRequestHttpException
     */
    public function onKernelRequest(RequestEvent $requestEvent): void
    {
        $request = $requestEvent->getRequest();
        if ($this->isPayloadlessMethod($request->getMethod())) {
            return;
        }

        $payload = $request->getContent();
        if (!$this->hasPayload($payload)) {
            return;
        }

        $data = $this->decodePayload($request);
        $request->request->replace($data);
    }

    /**
     * Checks if given HTTP method supports payload.
     */
    private function isPayloadlessMethod(string $method): bool
    {
        return \in_array($method, self::$methodsWithoutPayload, true);
    }

    /**
     * Checks if request contains payload.
     */
    private function hasPayload(string $payload): bool
    {
        return !empty($payload);
    }

    /**
     * Decodes request payload.
     *
     * @throws UnsupportedMediaTypeHttpException
     * @throws BadRequestHttpException
     */
    private function decodePayload(Request $request): array
    {
        try {
            $data = $this->decoderResolver->resolve((string) $request->getContentType())->decode($request->getContent());
        } catch (UnsupportedFormatException $e) {
            throw new UnsupportedMediaTypeHttpException('Provided format is not supported');
        } catch (InvalidPayloadException $e) {
            throw new BadRequestHttpException('Invalid payload provided');
        }

        return $data;
    }
}
