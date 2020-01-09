<?php

declare(strict_types=1);

namespace Weblabel\ApiBundle\Transformer\ExceptionTransformer;

use Weblabel\ApiBundle\Transformer\ExceptionTransformerInterface;
use Weblabel\ApiBundle\Transformer\ExceptionWrapper;

final class GenericExceptionTransformer implements ExceptionTransformerInterface
{
    private const DEFAULT_STATUS_CODE = 500;

    /** @var bool */
    private $debug;

    public function __construct(bool $debug)
    {
        $this->debug = $debug;
    }

    /**
     * {@inheritdoc}
     */
    public function transform(\Throwable $throwable): ExceptionWrapper
    {
        return new ExceptionWrapper(
            self::DEFAULT_STATUS_CODE,
            $this->getTitle($throwable),
            ExceptionWrapper::DEFAULT_TYPE,
            $this->getAttributes($throwable)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function supports(\Throwable $throwable): bool
    {
        return true;
    }

    /**
     * Returns exception message if debug is enabled or empty string otherwise.
     */
    private function getTitle(\Throwable $throwable): string
    {
        if (false === $this->debug) {
            return '';
        }

        return $throwable->getMessage();
    }

    /**
     * Returns exception details if debug is enabled.
     */
    private function getAttributes(\Throwable $throwable): array
    {
        if (false === $this->debug) {
            return [];
        }

        return [
            'details' => [
                'code' => $throwable->getCode(),
                'file' => $throwable->getFile(),
                'line' => $throwable->getLine(),
                'trace' => $throwable->getTrace(),
            ],
        ];
    }
}
