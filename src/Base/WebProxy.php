<?php

declare(strict_types=1);

namespace App\Base;

final class WebProxy
{
    /**
     * @var bool
     */
    private $useProxy;

    /**
     * @var null|string
     */
    private $proxyHost;

    /**
     * @var null|string
     */
    private $proxyPort;

    public function __construct(bool $useProxy, ?string $proxyHost, ?string $proxyPort)
    {
        $this->useProxy = $useProxy;
        $this->proxyHost = $proxyHost;
        $this->proxyPort = $proxyPort;
    }

    public function isProxyEnabled(): bool
    {
        return $this->useProxy;
    }

    public function getProxyHost(): ?string
    {
        return $this->proxyHost;
    }

    public function getProxyPort(): ?string
    {
        return $this->proxyPort;
    }

    public function getStreamContext(array $extend = [])
    {
        if ($this->isProxyEnabled()) {
            return stream_context_create(
                \array_merge([
                    'http' => [
                        'proxy' => 'tcp://'.$this->getProxyHost().':'.$this->getProxyPort(),
                        'request_fulluri' => true,
                    ],
                ], $extend)
            );
        }

        return null;
    }
}
