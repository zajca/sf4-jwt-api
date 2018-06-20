<?php

declare(strict_types=1);

namespace App\Security;

use Lexik\Bundle\JWTAuthenticationBundle\TokenExtractor\TokenExtractorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class JWTTokenAuthenticator extends AbstractGuardAuthenticator
{
    /**
     * @var TokenExtractorInterface
     */
    private $tokenExtractor;
    
    public function __construct(
        TokenExtractorInterface $tokenExtractor
    ) {
        $this->tokenExtractor = $tokenExtractor;
    }
    
    public function supports(Request $request)
    {
        return false !== $this->getTokenExtractor()->extract($request);
    }
    
    /**
     * {@inheritdoc}
     */
    public function getCredentials(Request $request)
    {
        $tokenExtractor = $this->getTokenExtractor();
        
        if (!$tokenExtractor instanceof TokenExtractorInterface) {
            throw new \RuntimeException(
                sprintf('Method "%s::getTokenExtractor()" must return an instance of "%s".', __CLASS__, TokenExtractorInterface::class)
            );
        }
        
        if (false === ($jsonWebToken = $tokenExtractor->extract($request))) {
            return;
        }
        
        return $jsonWebToken;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getUser($jsonWebToken, UserProviderInterface $userProvider)
    {
        $user = $userProvider->loadUserByUsername($jsonWebToken);
        
        return $user;
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
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return;
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
    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }
    
    /**
     * {@inheritdoc}
     */
    public function supportsRememberMe()
    {
        return false;
    }
    
    /**
     * Gets the token extractor to be used for retrieving a JWT token in the
     * current request.
     *
     * Override this method for adding/removing extractors to the chain one or
     * returning a different {@link TokenExtractorInterface} implementation.
     *
     * @return TokenExtractorInterface
     */
    protected function getTokenExtractor()
    {
        return $this->tokenExtractor;
    }
}
