<?php

declare(strict_types=1);

namespace App\Base;

use App\Entity\User;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

trait UserControllerTrait
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @required
     *
     * @param TokenStorageInterface $tokenStorage
     */
    public function setTokenStorage(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @throws \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
     *
     * @return UserInterface
     */
    public function getUser(): UserInterface
    {
        if (null === $token = $this->tokenStorage->getToken()) {
            throw new AccessDeniedHttpException();
        }
        
        /** @var User $user */
        $user = $token->getUser();
        
        if (!$user instanceof UserInterface) {
            throw new AccessDeniedHttpException();
        }

        return $user;
    }
}
