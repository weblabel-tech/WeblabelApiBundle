<?php

declare(strict_types=1);

namespace Weblabel\ApiBundle\Normalizer\Form;

use Symfony\Component\Form\FormInterface;

interface FormErrorNormalizerInterface
{
    /**
     * Normalizes form error objects into a set of errors.
     */
    public function normalize(FormInterface $form) : array;
}
