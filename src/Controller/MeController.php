<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\Routing\Attribute\Route;

use Symfony\Component\Serializer\SerializerInterface;

class MeController extends AbstractController
{
    public function __construct(private Security $security, private SerializerInterface $serializer)
    {       
    }

    #[Route('/api/me', name: 'app_me', methods:['GET'])]
    public function me(): JsonResponse
    {
        
         $user = $this->security->getUser();

         if (!$user) {
            return new JsonResponse(['error' => 'User not authenticated'], 401);
        }

       
        $data = $this->serializer->serialize($user, 'json', ['groups' => ['read:user:item']]);


        return new JsonResponse($data, 200, [], true);
    }
}

