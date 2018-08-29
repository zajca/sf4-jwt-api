<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\User;
use App\Service\UserAuth;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

/**
 * Class FormAuthenticator.
 */
class UserFormAuthenticator extends AbstractGuardAuthenticator
{
    /**
     * Default message for authentication failure.
     *
     * @var string
     */
    private $failMessage = 'Invalid credentials';

    /**
     * @var UserAuth
     */
    private $userAuth;

    public function __construct(UserAuth $userAuth)
    {
        $this->userAuth = $userAuth;
    }

    /**
     * {@inheritdoc}
     */
    public function getCredentials(Request $request)
    {
        return [
            'username' => $request->get('username'),
            'password' => $request->get('password'),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        try {
            $token = $this->userAuth->login($credentials);

            return $this->userAuth->getUserEntity($token);
        } catch (BadCredentialsException $exception) {
            throw new CustomUserMessageAuthenticationException($this->failMessage);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        /** @var User $user */
        $user = $token->getUser();

        return new JsonResponse(['token' => $user->getToken()]);
    }

    /**
     * {@inheritdoc}
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new JsonResponse(['message' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
    }

    /**
     * {@inheritdoc}
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new JsonResponse(
            [
                'message' => 'Authentication Required',
            ], Response::HTTP_UNAUTHORIZED
        );
    }

    /**
     * {@inheritdoc}
     */
    public function supportsRememberMe()
    {
        return false;
    }

    /**
     * @param Request $request
     *
     * @return bool|string
     */
    protected function getRefererPath(Request $request)
    {
        /** @var string|null $referer */
        $referer = $request->headers->get('referer');
        if (null === $referer) {
            return '';
        }

        return \parse_url($referer, PHP_URL_PATH);
    }

    /**
     * {@inheritdoc}
     */
    public function supports(Request $request)
    {
        return null !== $request->get('username') && null !== $request->get('password');
    }
}
