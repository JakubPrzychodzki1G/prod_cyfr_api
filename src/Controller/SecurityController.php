<?php

namespace App\Controller;

use ApiPlatform\Api\IriConverterInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use App\Entity\User;

class SecurityController extends AbstractController
{
    #[Route('/api/login', name: 'app_login', methods: ['POST'])]
    public function login(IriConverterInterface $iriConverter, #[CurrentUser] User $user = null): Response
    {
        if(!$user) return $this->json(['error' => 'Invalid header'], 401);

        // return new Response(null, 204, [
        //     'getLocation' => $iriConverter->getIriFromResource($user),
        // ]);
        return $this->json(array(
            "id"=> $user->getId(),
            "email"=> $user->getEmail(),
            "username"=> $user->getUsername(),
            "name"=> $user->getName(),
            "lastName"=> $user->getLastName(),
            "roles"=> $user->getRoles(),
            "isVerified"=> $user->isIsVerified()
        ), 202);
    }

    #[Route('/api/logout', name: 'app_logout')]
    public function logout()
    {
        throw new \Exception('this never be reached');
    }

    #[Route("/api/me", name: "app_me", methods: ['GET'])]
    public function getMe(#[CurrentUser] User $user = null): Response
    {   if(!$user) return $this->json(['error' => 'not logged in'], 401);
        return $this->json(array(
            "id"=> $user->getId(),
            "email"=> $user->getEmail(),
            "username"=> $user->getUsername(),
            "name"=> $user->getName(),
            "lastName"=> $user->getLastName(),
            "roles"=> $user->getRoles(),
            "isVerified"=> $user->isIsVerified()
        ), 200);
    }
}
