<?php declare(strict_types=1);

namespace App\Controller;

use App\Base\Rest\View;
use App\Base\UserControllerTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;

final class TestAdminAction
{
    
    use UserControllerTrait;
    
    /**
     * @Route("/api/test-admin", methods={"GET"})
     * @Security("has_role('RROLE_ADMIN')")
     */
    public function __invoke(): View
    {
        return new View($this->getUser());
    }
}
