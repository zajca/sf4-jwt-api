<?php

declare(strict_types=1);

namespace App\Base;

class DirService
{
    /**
     * @var string
     */
    private $kernelRootDir;

    /**
     * @var string
     */
    private $tempDir;

    public function __construct(string $kernelRootDir, string $tempDir)
    {
        $this->kernelRootDir = $kernelRootDir;
        $this->tempDir = $tempDir;
    }

    public function getTempDir(): string
    {
        return $this->tempDir;
    }

    public function getKernelRootDir(): string
    {
        return $this->kernelRootDir;
    }

    public function getWebDir(): string
    {
        return $this->kernelRootDir.'/../public/';
    }

    public function getProjectDir(): string
    {
        return $this->kernelRootDir.'/../';
    }
}
