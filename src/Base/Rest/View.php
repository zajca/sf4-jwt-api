<?php declare(strict_types=1);

namespace App\Base\Rest;

use Symfony\Component\HttpFoundation\Response;

class View
{
    
    /**
     * @var mixed
     */
    private $data;
    
    /**
     * @var string
     */
    private $format;
    
    /**
     * @var array
     */
    private $context;
    
    /**
     * @var int
     */
    private $statusCode;
    
    /**
     * @var array
     */
    private $headers;
    
    public function __construct($data, int $statusCode = Response::HTTP_OK, string $format = 'json', array $context = [], array $headers = [])
    {
        $this->data = $data;
        $this->format = $format;
        $this->context = $context;
        $this->statusCode = $statusCode;
        $this->headers = $headers;
    }
    
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
    
    public function getHeaders(): array
    {
        return $this->headers;
    }
    
    public function getData()
    {
        return $this->data;
    }
    
    public function getFormat(): string
    {
        return $this->format;
    }
    
    public function getContext(): array
    {
        return $this->context;
    }
    
}
