<?php

declare(strict_types=1);

namespace Weblabel\ApiBundle\Tests\Unit\Decoder;

use PHPUnit\Framework\TestCase;
use Weblabel\ApiBundle\Decoder\JsonDecoder;
use Weblabel\ApiBundle\Exception\InvalidPayloadException;

class JsonDecoderTest extends TestCase
{
    private $jsonDecoder;

    public function test_that_decoder_supports_json_format()
    {
        self::assertTrue($this->jsonDecoder->supports('json'));
    }

    /**
     * @dataProvider nonJsonFormatDataProvider
     */
    public function test_that_decoder_doesnt_support_non_json_format(string $format)
    {
        self::assertFalse($this->jsonDecoder->supports($format));
    }

    public function test_decoding_json_payload()
    {
        $result = $this->jsonDecoder->decode('{"foo": "bar"}');

        self::assertSame(['foo' => 'bar'], $result);
    }

    public function test_decoding_with_invalid_json_payload()
    {
        $this->expectException(InvalidPayloadException::class);

        $this->jsonDecoder->decode('{"foo":bar}');
    }

    public function nonJsonFormatDataProvider() : array
    {
        return [
            ['html'],
            ['txt'],
            ['xml'],
            ['jsonld'],
        ];
    }

    protected function setUp() : void
    {
        $this->jsonDecoder = new JsonDecoder();
    }
}
