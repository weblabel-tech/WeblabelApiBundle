<?php

declare(strict_types=1);

namespace Weblabel\ApiBundle\Exception;

class UnsupportedFormatException extends RuntimeException
{
    /**
     * Returns current exception with predefined message for given format.
     */
    public static function forFormat(string $format) : self
    {
        return new self(sprintf('Unsupported format "%s"', $format));
    }
}
