<?php

declare(strict_types=1);

namespace Weblabel\ApiBundle\Normalizer\Form;

use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintViolation;
use Weblabel\ApiBundle\Validator\ParametersAwareInterface;

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
            /** @var Constraint $constraint */
            $constraint = $cause->getConstraint();
            $errors[] = [
                'message' => $error->getMessage(),
                'code' => $constraint->payload['errorCode'] ?? -1,
                'parameters' => $this->getConstraintParameters($constraint),
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

    private function getConstraintParameters(Constraint $constraint): array
    {
        if ($constraint instanceof ParametersAwareInterface) {
            return $constraint->getParameters();
        }

        return [];
    }
}
