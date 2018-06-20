<?php declare(strict_types=1);

namespace App\Controller;

use App\Base\Rest\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

final class FailAction
{
    
    /**
     * @Route("/no-api/fail", methods={"GET"})
     */
    public function __invoke(): View
    {
        throw new NotFoundHttpException();
    }
}
