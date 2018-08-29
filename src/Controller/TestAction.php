<?php

declare(strict_types=1);

namespace App\Controller;

use App\Base\Rest\View;
use App\Base\UserControllerTrait;
use Symfony\Component\Routing\Annotation\Route;

final class TestAction
{
    use UserControllerTrait;

    /**
     * @Route("/api/test", methods={"GET"})
     */
    public function __invoke(): View
    {
        return new View($this->getUser());
    }
}
