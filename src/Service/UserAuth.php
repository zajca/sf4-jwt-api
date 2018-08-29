<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\InvalidTokenException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class UserAuth
{
    /**
     * @var JWTEncoderInterface
     */
    private $encoder;

    /**
     * @var Client
     */
    private $guzzle;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em, Client $guzzle, JWTEncoderInterface $encoder, UserRepository $userRepository)
    {
        $this->encoder = $encoder;
        $this->guzzle = $guzzle;
        $this->userRepository = $userRepository;
        $this->em = $em;
    }

    public function checkToken($token): void
    {
        $response = $this->guzzle->get(
            '/api/token_check',
            [
                RequestOptions::HEADERS => ['authorization' => 'Bearer '.$token],
                RequestOptions::HTTP_ERRORS => false,
            ]
        );
        if (Response::HTTP_OK !== $response->getStatusCode()) {
            throw new InvalidTokenException();
        }
    }

    public function login($credentials): string
    {
        $response = $this->guzzle->post(
            '/login_check',
            [
                RequestOptions::JSON => [
                    'username' => $credentials['username'],
                    'password' => $credentials['password'],
                ],
                RequestOptions::HTTP_ERRORS => false,
            ]
        );

        if (Response::HTTP_OK !== $response->getStatusCode()) {
            throw new BadCredentialsException();
        }
        $data = @json_decode((string) $response->getBody(), true);
        if (!\array_key_exists('token', $data)) {
            throw new BadCredentialsException();
        }

        return $data['token'];
    }

    public function isTokenRegistered($token): bool
    {
        return null !== $this->userRepository->findByToken($token);
    }

    public function getUserEntity($token): User
    {
        $user = $this->userRepository->findByToken($token);
        if (null === $user) {
            $payload = $this->encoder->decode($token);
            $user = User::createFromPayload($token, $payload);
            $this->em->persist($user);
            $this->em->flush();
        }

        return $user;
    }
}
