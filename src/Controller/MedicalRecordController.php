<?php

namespace App\Controller;

use App\MedicalRecord\CreateMedicalRecordDto;
use App\Service\MedicalRecordService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/record')]
class MedicalRecordController extends AbstractController
{
    private readonly ValidatorInterface $validatorInterface;
    private readonly SerializerInterface $serializer;
    private readonly MedicalRecordService $medicalRecordService;
    public function __construct(ValidatorInterface $validatorInterface, SerializerInterface $serializer,
                                MedicalRecordService $medicalRecordService)
    {
        $this->validatorInterface = $validatorInterface;
        $this->serializer = $serializer;
        $this->medicalRecordService = $medicalRecordService;
    }


    #[IsGranted('ROLE_DOCTOR')]
    #[Route('/create', methods: ['POST'])]
    public function create(Request $request, TokenStorageInterface $tokenStorage): JsonResponse
    {
        $authDoctor = $tokenStorage->getToken()->getUser();

        $dto = $this->serializer->deserialize($request->getContent(), CreateMedicaLRecordDto::class, 'json');
        $errors = $this->validatorInterface->validate($dto);
        if(count($errors) > 0)
            return $this->json($errors, 422);

        $result = $this->medicalRecordService->createMedicalService($dto, $authDoctor);
        return $this->json($result, 201);
    }


    #[IsGranted('ROLE_PATIENT')]
    #[Route('/show-patient', methods: ['GET'])]
    public function showPatientMedicalRecords(TokenStorageInterface $tokenStorage): JsonResponse
    {
        $authPatient = $tokenStorage->getToken()->getUser();
        $result = $this->medicalRecordService->findPatientRecords($authPatient);
        return $this->json($result, 200);
    }
}
