<?php

declare(strict_types=1);

namespace Weblabel\ApiBundle\Normalizer\Form;

use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\ConstraintViolation;

final class FormErrorNormalizer implements FormErrorNormalizerInterface
{
    /**
     * {@inheritdoc}
     */
    public function normalize(FormInterface $form): array
    {
        $errors = [];
        /** @var FormError $error */
        foreach ($form->getErrors() as $error) {
            /** @var ConstraintViolation $cause */
            $cause = $error->getCause();
            $errors[] = [
                'message' => $error->getMessage(),
                'code' => $cause->getConstraint()->payload['errorCode'],
            ];
        }

        foreach ($form->all() as $childForm) {
            if (!$childForm instanceof FormInterface) {
                continue;
            }

            $childFormErrors = $this->normalize($childForm);
            if (!empty($childFormErrors)) {
                $errors[$childForm->getName()] = $childFormErrors;
            }
        }

        return $errors;
    }
}
