<?php

namespace App\Entity;

use App\Exception\InvalidAuthResponseException;
use Doctrine\ORM\Mapping as ORM;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     * @ORM\Column(type="text")
     */
    private $token;
    
    /**
     * @ORM\Column(type="datetime")
     */
    private $expiresAt;
    
    /**
     * @var array
     * @ORM\Column(name="roles",type="simple_array")
     */
    private $roles = [];
    
    /**
     * @ORM\Column(type="text")
     */
    private $userId;
    
    public static function createFromPayload($token, $payload)
    {
        $user = new self;
        $user->token = $token;
        $user->roles = $payload['roles'];
        $user->userId = $payload['id'];
        $expiresAt = new \DateTime;
        $user->expiresAt = $expiresAt->setTimestamp($payload['exp']);
        
        return $user;
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getToken(): ?string
    {
        return $this->token;
    }
    
    public function setToken(string $token): self
    {
        $this->token = $token;
        
        return $this;
    }
    
    public function getExpiresAt(): ?\DateTimeInterface
    {
        return $this->expiresAt;
    }
    
    public function setExpiresAt(\DateTimeInterface $expiresAt): self
    {
        $this->expiresAt = $expiresAt;
        
        return $this;
    }
    
    public static function createFromAuthResponse(ResponseInterface $response)
    {
        if (Response::HTTP_OK !== $response->getStatusCode()) {
            throw new InvalidAuthResponseException;
        }
        $data = json_decode($response->getBody());
        if ($token = \array_key_exists('token', $data)) {
            $user = new self;
            $user->token = $token;
            
            return $user;
        }
        
        throw new InvalidAuthResponseException;
    }
    
    public function getUserId()
    {
        return $this->userId;
    }
    
    public function getRoles()
    {
        return $this->roles;
    }
    
    public function getPassword()
    {
    }
    
    public function getSalt()
    {
    }
    
    public function getUsername()
    {
    }
    
    public function eraseCredentials()
    {
    }
}
