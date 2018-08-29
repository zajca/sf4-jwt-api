<?php

declare(strict_types=1);

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;

class AccessDeniedHandler implements AccessDeniedHandlerInterface
{
    /**
     * {@inheritdoc}
     *
     * @throws \InvalidArgumentException
     */
    public function handle(Request $request, AccessDeniedException $accessDeniedException)
    {
        return new Response(
            \json_encode(
                [
                    'message' => 'Nemáte dostatečné oprávnění k této akci',
                    'code' => Response::HTTP_FORBIDDEN,
                ]
            ),
            Response::HTTP_FORBIDDEN
        );
    }
}
