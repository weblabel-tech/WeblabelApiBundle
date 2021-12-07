<?php

declare(strict_types=1);

namespace Weblabel\ApiBundle\Validator;

interface ParametersAwareInterface
{
    public function getParameters(): array;
}
