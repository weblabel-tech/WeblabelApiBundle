<?php

declare(strict_types=1);

namespace Weblabel\ApiBundle\Tests\Unit\Decoder;

use PHPUnit\Framework\TestCase;
use Weblabel\ApiBundle\Decoder\DecoderInterface;
use Weblabel\ApiBundle\Decoder\DecoderResolver;
use Weblabel\ApiBundle\Exception\UnsupportedFormatException;

class DecoderResolverTest extends TestCase
{
    public function test_decoder_resolving_for_non_supported_format()
    {
        $this->expectException(UnsupportedFormatException::class);
        $decoderResolver = new DecoderResolver([]);
        $decoderResolver->resolve('json');
    }

    public function test_decoder_resolving()
    {
        $xmlDecoder = $this->createMock(DecoderInterface::class);
        $xmlDecoder
            ->expects(self::once())
            ->method('supports')
            ->with('json')
            ->willReturn(false);

        $jsonDecoder = $this->createMock(DecoderInterface::class);
        $jsonDecoder
            ->expects(self::once())
            ->method('supports')
            ->with('json')
            ->willReturn(true);

        $decoderResolver = new DecoderResolver([$xmlDecoder, $jsonDecoder]);
        $decoder = $decoderResolver->resolve('json');

        self::assertSame($decoder, $jsonDecoder);
    }
}
