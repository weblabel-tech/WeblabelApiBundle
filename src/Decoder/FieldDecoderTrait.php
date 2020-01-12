<?php

declare(strict_types=1);

namespace Weblabel\ApiBundle\Decoder;

trait FieldDecoderTrait
{
    /** @var DecoderInterface */
    private $decoder;

    public function decodeFields(array $data, array $fields): array
    {
        foreach ($fields as $field) {
            $data[$field] = $this->decoder->decode($data[$field]);
        }

        return $data;
    }

    public function setDecoder(DecoderInterface $decoder): void
    {
        $this->decoder = $decoder;
    }
}
