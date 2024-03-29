<?php

namespace App\Controller;

use App\Dto\CreateManagerDto;
use App\Service\ManagerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use OpenApi\Attributes as OA;


#[OA\Tag(name: 'manager')]
#[Route('/manager')]
class ManagerController extends AbstractController
{
    private readonly ValidatorInterface $validatorInterface;
    private readonly SerializerInterface $serializer;
    private readonly ManagerService $managerService;
    public function __construct(ValidatorInterface $validatorInterface, SerializerInterface $serializer,
                                ManagerService $managerService)
    {
        $this->validatorInterface = $validatorInterface;
        $this->serializer = $serializer;
        $this->managerService = $managerService;
    }


    #[Route('/create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $dto = $this->serializer->deserialize($request->getContent(), CreateManagerDto::class, 'json');

        $errors = $this->validatorInterface->validate($dto);
        if (count($errors) > 0)
            return $this->json($errors, 422);

        $result = $this->managerService->createManager($dto);
        return $this->json($result, 201);
    }


    #[IsGranted('ROLE_MANAGER')]
    #[Route('/info', methods: ['GET'])]
    public function authManager(TokenStorageInterface $tokenStorage): JsonResponse
    {
        $authUser = $tokenStorage->getToken()->getUser();
        $result = $this->managerService->getAuthManager($authUser);

        return $this->json($result, 200);
    }
}

