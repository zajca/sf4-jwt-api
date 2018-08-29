<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\Base\Rest\View;
use App\Base\UserControllerTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

final class UserController
{
    use UserControllerTrait;

    /**
     * @Route("/api/user/me", name="user_me")
     * This route will check if user is logged in and if yes will return login object, if not will throw 403
     *
     * @throws \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
     *
     * @return View
     */
    public function fetchUserAction(): View
    {
        $user = $this->getUser();

        return new View($user);
    }
}
