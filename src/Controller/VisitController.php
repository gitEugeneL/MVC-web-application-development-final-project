<?php

namespace App\Controller;

use App\Service\VisitService;
use App\Visit\CreateVisitDto;
use App\Visit\FindFreeTimeDto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use OpenApi\Attributes as OA;


#[OA\Tag(name: 'visit')]
#[Route('/visit')]
class VisitController extends AbstractController
{
    private readonly ValidatorInterface $validatorInterface;
    private readonly SerializerInterface $serializer;
    private readonly VisitService $visitService;
    public function __construct(ValidatorInterface $validatorInterface, SerializerInterface $serializer,
                                VisitService $visitService)
    {
        $this->validatorInterface = $validatorInterface;
        $this->serializer = $serializer;
        $this->visitService = $visitService;
    }


    #[IsGranted('ROLE_PATIENT')]
    #[Route('/time', methods: ['POST'])]
    public function findFreeDayTime(Request $request): JsonResponse
    {
        $dto = $this->serializer->deserialize($request->getContent(), FindFreeTimeDto::class, 'json');
        $errors = $this->validatorInterface->validate($dto);
        if(count($errors) > 0)
            return $this->json($errors, 422);

        $freeDayTime = $this->visitService->findFreeDayTime($dto);

        return $this->json($freeDayTime, 200);
    }


    #[IsGranted('ROLE_PATIENT')]
    #[Route('/create', methods: ['POST'])]
    public function create(TokenStorageInterface $tokenStorage, Request $request): JsonResponse
    {
        $authUser = $tokenStorage->getToken()->getUser();
        $dto = $this->serializer->deserialize($request->getContent(), CreateVisitDto::class, 'json');

        $errors = $this->validatorInterface->validate($dto);
        if(count($errors) > 0)
            return $this->json($errors, 422);

        $visit = $this->visitService->createVisit($authUser, $dto);
        return $this->json($visit, 201);
    }


    #[IsGranted('ROLE_PATIENT')]
    #[Route('/show-patient-future', methods: ['GET'])]
    public function findVisitsByAuthPatient(TokenStorageInterface $tokenStorage): JsonResponse
    {
        $authPatient = $tokenStorage->getToken()->getUser();
        $result = $this->visitService->showVisitsByPatient($authPatient);
        return $this->json($result, 200);
    }


    #[IsGranted('ROLE_DOCTOR')]
    #[Route('/show-doctor-future', methods: ['GET'])]
    public function findVisitsByAuthDoctor(TokenStorageInterface $tokenStorage): JsonResponse
    {
        $authDoctor = $tokenStorage->getToken()->getUser();
        $result = $this->visitService->showVisitsByDoctor($authDoctor);
        return $this->json($result, 200);
    }


    #[IsGranted('ROLE_MANAGER')]
    #[Route('/show-manager', methods: ['GET'])]
    public function findVisits(): JsonResponse
    {
        $result = $this->visitService->showVisits();
        return $this->json($result, 200);
    }


    #[IsGranted('ROLE_DOCTOR')]
    #[Route('/update/{visitId}', methods: ['PATCH'])]
    public function updateVisit(Request $request): JsonResponse
    {
        $this->visitService->updateVisit($request->get('visitId'));
        return $this->json("success!", 200);
    }
}
