<?php

declare(strict_types=1);

namespace Weblabel\ApiBundle\Controller;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Weblabel\ApiBundle\Exception\ConflictException;
use Weblabel\ApiBundle\Exception\ValidationException;
use Weblabel\ApiBundle\Normalizer\Form\FormErrorNormalizerInterface;
use Weblabel\ApiBundle\Security\AuthorizationChecker;

abstract class AbstractController
{
    protected FormErrorNormalizerInterface $formErrorNormalizer;

    protected AuthorizationChecker $authorizationChecker;

    public function setFormErrorNormalizer(FormErrorNormalizerInterface $formErrorNormalizer): void
    {
        $this->formErrorNormalizer = $formErrorNormalizer;
    }

    public function setAuthorizationChecker(AuthorizationChecker $authorizationChecker): void
    {
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * @param array|object $data
     */
    protected function json($data, int $statusCode = Response::HTTP_OK, array $headers = []): JsonResponse
    {
        return new JsonResponse(['data' => $data], $statusCode, $headers);
    }

    /**
     * Handles form submit and validation.
     */
    protected function handleForm(Request $request, FormInterface $form): void
    {
        $form->submit($request->request->all());
        if (!$form->isValid()) {
            $errors = $this->formErrorNormalizer->normalize($form);

            throw $this->createValidationException($errors);
        }
    }

    protected function createValidationException(array $errors, string $message = 'Validation error', \Throwable $previous = null): ValidationException
    {
        return new ValidationException($errors, $message, $previous);
    }

    protected function createConflictException(array $errors, string $message = 'Conflict', \Throwable $previous = null): ConflictException
    {
        return new ConflictException($errors, $message, $previous);
    }

    /**
     * @param mixed $attributes
     * @param mixed $subject
     */
    protected function isGranted($attributes, $subject = null): bool
    {
        return $this->authorizationChecker->isGranted($attributes, $subject);
    }

    /**
     * @param mixed $attributes
     * @param mixed $subject
     */
    protected function allowAccess($attributes, $subject = null, string $message = 'Access Denied'): void
    {
        if (!$this->isGranted($attributes, $subject)) {
            throw $this->createAccessDeniedException($message, (array) $attributes, $subject);
        }
    }

    /**
     * @param mixed $subject
     */
    protected function createAccessDeniedException(string $message = 'Access Denied', array $attributes = [], $subject = null, \Throwable $previous = null): AccessDeniedException
    {
        $exception = new AccessDeniedException($message, $previous);
        $exception->setAttributes($attributes);
        $exception->setSubject($subject);

        return $exception;
    }
}
