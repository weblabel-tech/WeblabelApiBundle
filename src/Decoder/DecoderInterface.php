<?php

declare(strict_types=1);

namespace Weblabel\ApiBundle\Decoder;

use Weblabel\ApiBundle\Exception\InvalidPayloadException;

interface DecoderInterface
{
    /**
     * Decodes a string into an array.
     *
     * @throws InvalidPayloadException
     */
    public function decode(string $payload) : array;

    /**
     * Checks if given format is supported.
     */
    public function supports(string $format) : bool;
}
