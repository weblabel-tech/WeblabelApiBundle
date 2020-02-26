<?php

declare(strict_types=1);

namespace Weblabel\ApiBundle\Controller;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Weblabel\ApiBundle\Exception\ValidationException;
use Weblabel\ApiBundle\Normalizer\Form\FormErrorNormalizerInterface;

abstract class AbstractController
{
    /** @var FormErrorNormalizerInterface */
    private FormErrorNormalizerInterface $formErrorNormalizer;

    public function setFormErrorNormalizer(FormErrorNormalizerInterface $formErrorNormalizer): void
    {
        $this->formErrorNormalizer = $formErrorNormalizer;
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
}
