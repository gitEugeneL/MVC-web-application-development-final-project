<?php

use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @Annotation
 */
#[Attribute] class IsGrantedOneOf
{
    private array $roles;

    public function __construct(array $roles)
    {
        $this->roles = $roles;
    }

    public function checkAccess(AuthorizationCheckerInterface $authorizationChecker): void
    {
        foreach ($this->roles as $role) {
            if ($authorizationChecker->isGranted($role)) {
                return;
            }
        }
        throw new AccessDeniedException('Access denied.');
    }
}