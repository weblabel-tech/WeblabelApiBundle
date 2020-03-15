<?php

declare(strict_types=1);

namespace Weblabel\ApiBundle\Controller;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
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
            $this->throwValidationException($form);
        }
    }

    /**
     * Throws validation exception.
     *
     * @throws ValidationException
     */
    protected function throwValidationException(FormInterface $form): void
    {
        $errors = $this->formErrorNormalizer->normalize($form);

        throw new ValidationException($errors);
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
    protected function denyAccessUnlessGranted($attributes, $subject = null, string $message = 'Access Denied.'): void
    {
        if (!$this->isGranted($attributes, $subject)) {
            $this->throwAccessDeniedException($message, $attributes, $subject);
        }
    }

    /**
     * @param array|string $attributes
     * @param mixed        $subject
     *
     * @throws AccessDeniedException
     */
    protected function throwAccessDeniedException(string $message, $attributes, $subject): void
    {
        $exception = new AccessDeniedException($message);
        $exception->setAttributes($attributes);
        $exception->setSubject($subject);

        throw $exception;
    }
}
