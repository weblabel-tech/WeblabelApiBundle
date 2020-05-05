<?php

declare(strict_types=1);

namespace Weblabel\ApiBundle\Exception;

use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class ConflictException extends ConflictHttpException
{
    /** @var array */
    private array $errors;

    public function __construct(array $errors, string $message = 'Conflict', \Throwable $previous = null, int $code = 0, array $headers = [])
    {
        $this->errors = $errors;
        parent::__construct($message, $previous, $code, $headers);
    }

    /**
     * Gets errors.
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
