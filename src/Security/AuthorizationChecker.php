<?php

declare(strict_types=1);

namespace Weblabel\ApiBundle\Security;

use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

final class AuthorizationChecker
{
    private AuthorizationCheckerInterface $authorizationChecker;

    public function __construct(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * @param mixed $attributes
     * @param mixed $subject
     */
    public function isGranted($attributes, $subject = null): bool
    {
        return $this->authorizationChecker->isGranted($attributes, $subject);
    }
}
