<?php

namespace App\Controller;

use App\Office\CreateOfficeDto;
use App\Service\OfficeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


#[Route('/office')]
class OfficeController extends AbstractController
{
    private readonly ValidatorInterface $validatorInterface;
    private readonly SerializerInterface $serializer;
    private readonly OfficeService $officeService;

    public function __construct(ValidatorInterface $validatorInterface, SerializerInterface $serializer,
                                OfficeService      $officeService)
    {
        $this->validatorInterface = $validatorInterface;
        $this->serializer = $serializer;
        $this->officeService = $officeService;
    }

    #[IsGranted('ROLE_MANAGER')]
    #[Route('/create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $dto = $this->serializer->deserialize($request->getContent(), CreateOfficeDto::class, 'json');
        $errors = $this->validatorInterface->validate($dto);
        if (count($errors) > 0)
            return $this->json($errors, 422);

        $result = $this->officeService->createOffice($dto);
        return $this->json($result, 201);
    }


    #[IsGranted('ROLE_MANAGER')]
    #[Route('/show', methods: ['GET'])]
    public function show(): JsonResponse
    {
        $result = $this->officeService->showOffice();
        return $this->json($result, 200);
    }


    #[IsGranted('ROLE_MANAGER')]
    #[Route('/update/{id}', methods: ['PATCH'])]
    public function update(Request $request): JsonResponse
    {
        $result = $this->officeService->updateOffice($request->get('id'));
        return $this->json($result, 200);
    }

}