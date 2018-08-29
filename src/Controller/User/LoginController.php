<?php

declare(strict_types=1);

namespace App\Controller\User;

use Symfony\Component\Routing\Annotation\Route;

final class LoginController
{
    /**
     * @Route("login", name="login")
     *
     * Login handled by guard UserFormAuthenticator
     *
     * @throws \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function loginAction()
    {
    }

    /**
     * @Route("/logout", name="logout")
     * Login handled by Symfony
     */
    public function logoutAction()
    {
    }
}
