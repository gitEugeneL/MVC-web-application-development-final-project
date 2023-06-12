<?php

namespace App\Controller;

use App\Entity\User;
use App\Security\AccessTokenHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use OpenApi\Attributes as OA;


#[OA\Tag(name: 'auth')]
class AuthController extends AbstractController
{
    #[Route('/login', name: 'app_auth_login', methods:['POST'])]
    public function login(#[CurrentUser] User $user, AccessTokenHandler $accessTokenHandler): JsonResponse
    {
        if (!$user)
            return $this->json('Invalid credentials', 401);

        $token = $accessTokenHandler->createForUser($user);
        return $this->json(['token' => $token], 200);
    }
}
