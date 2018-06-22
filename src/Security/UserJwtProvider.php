<?php declare(strict_types=1);

namespace App\Security;

use App\Entity\User;
use App\Exception\InvalidAuthResponseException;
use App\Repository\UserRepository;
use App\Service\UserAuth;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\InvalidTokenException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;



class UserJwtProvider implements UserProviderInterface
{
    
    /**
     * @var Client
     */
    private $auth;
    
    /**
     * @var UserRepository
     */
    private $userRepository;
    
    /**
     * @var UserAuth
     */
    private $userAuth;
    
    /**
     * @var EntityManagerInterface
     */
    private $em;
    
    public function __construct(Client $auth, UserAuth $userAuth, UserRepository $userRepository, EntityManagerInterface $em)
    {
        $this->auth = $auth;
        $this->userRepository = $userRepository;
        $this->userAuth = $userAuth;
        $this->em = $em;
    }
    
    public function loadUserByUsername($username)
    {
        $token = $username;
        $user = $this->userRepository->findByToken($token);
        
        if (null === $user) {
            try {
                $this->userAuth->checkToken($token);
                
                return $this->userAuth->getUserEntity($token);
            } catch (InvalidTokenException $exception) {
                throw new UsernameNotFoundException('Invalid JWT token');
            }
        }
        
        return $user;
    }
    
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', get_class($user))
            );
        }
        
        return $this->loadUserByUsername($user->getUsername());
    }
    
    public function supportsClass($class)
    {
        return User::class === $class;
    }
}
