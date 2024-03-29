<?php

namespace App\Controller;

use App\Service\SpecializationService;
use App\Specialization\CreateSpecializationDto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\ExpressionLanguage\Expression;
use OpenApi\Attributes as OA;


#[OA\Tag(name: 'specialization')]
#[Route('/specialization')]
class SpecializationController extends AbstractController
{
    private readonly ValidatorInterface $validatorInterface;
    private readonly SerializerInterface $serializer;
    private readonly SpecializationService $specializationService;
    public function __construct(ValidatorInterface $validatorInterface, SerializerInterface $serializer,
                                SpecializationService $specializationService)
    {
        $this->validatorInterface = $validatorInterface;
        $this->serializer = $serializer;
        $this->specializationService = $specializationService;
    }


    #[IsGranted('ROLE_MANAGER')]
    #[Route('/create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $dto = $this->serializer->deserialize($request->getContent(), CreateSpecializationDto::class, 'json');
        $errors = $this->validatorInterface->validate($dto);
        if(count($errors) > 0)
            return $this->json($errors, 422);

        $result = $this->specializationService->createSpecialization($dto);
        return $this->json($result, 201);
    }


    #[IsGranted(new Expression('is_granted("ROLE_PATIENT") or is_granted("ROLE_MANAGER")'))]
    #[Route('/show', methods: ['GET'])]
    public function show(): JsonResponse
    {
        $result = $this->specializationService->showSpecializations();
        return $this->json($result, 200);
    }
}
