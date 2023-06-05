<?php

namespace App\Controller;

use App\Patient\CreatePatientDto;
use App\Service\PatientService;
use IsGrantedOneOf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


#[Route('/patient')]
class PatientController extends AbstractController
{
    private readonly ValidatorInterface $validatorInterface;
    private readonly SerializerInterface $serializer;
    private readonly PatientService $patientService;
    public function __construct(ValidatorInterface $validatorInterface, SerializerInterface $serializer,
                                PatientService $patientService)
    {
        $this->validatorInterface = $validatorInterface;
        $this->serializer = $serializer;
        $this->patientService = $patientService;
    }


    #[Route('/create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $dto = $this->serializer->deserialize($request->getContent(), CreatePatientDto::class, 'json');
        $errors = $this->validatorInterface->validate($dto);
        if(count($errors) > 0)
            return $this->json($errors, 422);

        $result = $this->patientService->createPatient($dto);
        return $this->json($result, 201);
    }

    #[IsGrantedOneOf(['ROLE_DOCTOR', 'ROLE_MANAGER'])]
    #[Route('/show', methods: ['GET'])]
    public function show(): JsonResponse
    {
        $result = $this->patientService->showPatients();
        return $this->json($result, 200);
    }


    #[IsGranted('ROLE_PATIENT')]
    #[Route('/info', methods: ['GET'])]
    public function authPatient(TokenStorageInterface $tokenStorage): JsonResponse
    {
        $authUser = $tokenStorage->getToken()->getUser();
        $result = $this->patientService->getAuthPatient($authUser);

        return $this->json($result, 200);
    }

}