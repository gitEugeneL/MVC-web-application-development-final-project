<?php

namespace App\Controller;

use App\Doctor\CreateDoctorDto;
use App\Doctor\UpdateDoctorSpecializationDto;
use App\Service\DoctorService;
use IsGrantedOneOf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


#[Route('/doctor')]
class DoctorController extends AbstractController
{
    private readonly ValidatorInterface $validatorInterface;
    private readonly SerializerInterface $serializer;
    private readonly DoctorService $doctorService;
    public function __construct(ValidatorInterface $validatorInterface, SerializerInterface $serializer,
                                DoctorService $doctorService)
    {
        $this->validatorInterface = $validatorInterface;
        $this->serializer = $serializer;
        $this->doctorService = $doctorService;
    }


    #[IsGranted('ROLE_MANAGER')]
    #[Route('/create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $dto = $this->serializer->deserialize($request->getContent(), CreateDoctorDto::class, 'json');
        $errors = $this->validatorInterface->validate($dto);
        if(count($errors) > 0)
            return $this->json($errors, 422);

        $result = $this->doctorService->createDoctor($dto);
        return $this->json($result, 201);
    }


    #[IsGranted('ROLE_MANAGER')]
    #[Route('/show', methods: ['GET'])]
    public function show(): JsonResponse
    {
        $result = $this->doctorService->showDoctors();
        return $this->json($result, 200);
    }


    #[IsGranted('ROLE_DOCTOR')]
    #[Route('/info', methods: ['GET'])]
    public function authDoctor(TokenStorageInterface $tokenStorage): JsonResponse
    {
        $authUser = $tokenStorage->getToken()->getUser();
        $result = $this->doctorService->getAuthDoctor($authUser);

        return $this->json($result, 200);
    }


    #[IsGrantedOneOf(['ROLE_PATIENT', 'ROLE_MANAGER'])]
    #[Route('/show/{specializationId}', methods: ['GET'])]
    public function findBySpecialization(Request $request): JsonResponse
    {
        $doctors = $this->doctorService->findDoctorsBySpecialization((int) $request->get('specializationId'));
        return $this->json($doctors, 200);
    }


    #[IsGranted('ROLE_MANAGER')]
    #[Route('/add-specialization/{doctorId}', methods: ['PATCH'])]
    public function addSpecialization(Request $request): JsonResponse
    {
        $dto = $this->serializer->deserialize($request->getContent(), UpdateDoctorSpecializationDto::class, 'json');
        $errors = $this->validatorInterface->validate($dto);
        if(count($errors) > 0)
            return $this->json($errors, 422);

        $result = $this->doctorService->updateSpecialization($dto, (int) $request->get('doctorId'));
        return $this->json($result, 201);
    }


    #[IsGranted('ROLE_MANAGER')]
    #[Route('/delete-specialization/{doctorId}', methods: ['PATCH'])]
    public function deleteSpecialization(Request $request): JsonResponse
    {
        $dto = $this->serializer->deserialize($request->getContent(), UpdateDoctorSpecializationDto::class, 'json');
        $errors = $this->validatorInterface->validate($dto);
        if(count($errors) > 0)
            return $this->json($errors, 422);

        $result = $this->doctorService->updateSpecialization($dto, (int) $request->get('doctorId'), true);
        return $this->json($result, 201);
    }
}
