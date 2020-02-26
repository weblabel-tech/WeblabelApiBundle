<?php

declare(strict_types=1);

namespace Weblabel\ApiBundle\Exception;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ValidationException extends BadRequestHttpException
{
    /** @var array */
    private array $errors;

    public function __construct(array $errors, string $message = 'Validation error', \Throwable $previous = null, int $code = 0, array $headers = [])
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
