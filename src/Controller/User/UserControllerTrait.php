<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\Entity\User;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

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
     * @return User
     */
    public function getUser(): User
    {
        if (null === $token = $this->tokenStorage->getToken()) {
            throw new AccessDeniedHttpException();
        }

        if (!is_object($user = $token->getUser())) {
            throw new AccessDeniedHttpException();
        }

        return $user;
    }
}
