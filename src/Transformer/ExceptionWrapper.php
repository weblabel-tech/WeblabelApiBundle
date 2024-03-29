<?php

declare(strict_types=1);

namespace Weblabel\ApiBundle\Transformer;

final class ExceptionWrapper
{
    public const DEFAULT_MESSAGE = 'Application error';

    public const DEFAULT_TYPE = 'about:blank';

    private int $statusCode;

    private string $title;

    private string $type;

    private array $attributes;

    public function __construct(int $status, string $title = self::DEFAULT_MESSAGE, string $type = self::DEFAULT_TYPE, array $attributes = [])
    {
        $this->statusCode = $status;
        $this->title = empty($title) ? self::DEFAULT_MESSAGE : $title;
        $this->type = $type;
        $this->attributes = $attributes;
    }

    /**
     * Gets array representation of injected attributes.
     */
    public function toArray(): array
    {
        return array_merge(
            [
                'status' => $this->statusCode,
                'title' => $this->title,
                'type' => $this->type,
            ],
            $this->attributes
        );
    }

    /**
     * Gets status code.
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
