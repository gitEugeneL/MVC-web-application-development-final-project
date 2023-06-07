<?php

namespace App\Service;

use App\Doctor\CreateDoctorDto;
use App\Doctor\GetDoctorDto;
use App\Doctor\UpdateDoctorSpecializationDto;
use App\Entity\Doctor;
use App\Entity\User;
use App\Exception\ApiException;
use App\Repository\DoctorRepository;
use App\Repository\SpecializationRepository;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\User\UserInterface;

class DoctorService
{
    private UserRepository $userRepository;
    private DoctorRepository $doctorRepository;
    private SpecializationRepository $specializationRepository;
    private ApiException $apiException;
    public function __construct(UserRepository $userRepository, DoctorRepository $doctorRepository,
                                SpecializationRepository $specializationRepository, ApiException $apiException)
    {
        $this->doctorRepository = $doctorRepository;
        $this->userRepository = $userRepository;
        $this->apiException = $apiException;
        $this->specializationRepository = $specializationRepository;
    }

    public function createDoctor(CreateDoctorDto $dto): GetDoctorDto
    {
        if ($this->userRepository->findOneBy(['email' => $dto->getEmail()]) !== null)
            $this->apiException->exception("This Doctor already exist", 422);

        $specializationsIds = $dto->getSpecializationsId();
        $specializations = [];

        foreach ($specializationsIds as $specializationsId) {
            $specialization = $this->specializationRepository->find($specializationsId);
            if (!$specialization)
                $this->apiException->exception("Specialization id: {$specializationsId} doesn't exist", 422);

            $specializations[] = $specialization;
        }

        $doctor = (new Doctor())
            ->setFirstName($dto->getFirstName())
            ->setLastName($dto->getLastName())
            ->setPhone($dto->getPhone())
            ->setAuthUser((new User())
                ->setEmail($dto->getEmail())
                ->setPassword(password_hash($dto->getPassword(), PASSWORD_DEFAULT))
                ->setRoles(["ROLE_DOCTOR"]));

        foreach ($specializations as $specialization) {
            $doctor->addSpecialization($specialization);
        }

        $this->doctorRepository->save($doctor, true);

        return $this->createGetDoctorDto($doctor);
    }


    public function showDoctors(): array
    {
        $doctors = $this->doctorRepository->findAll();
        $doctorsDTOs = [];
        foreach ($doctors as $doctor) {
            $doctorsDTOs[] = $this->createGetDoctorDto($doctor);
        }
        return $doctorsDTOs;
    }


    public function getAuthDoctor(UserInterface $authUser): GetDoctorDto
    {
        $doctor = $this->doctorRepository
            ->findOneBy(["authUser" => $this->userRepository
                ->findOneBy(['email' => $authUser->getUserIdentifier()])]);

        if(!$doctor)
            $this->apiException->exception("This Doctor doesn't exist", 422);

        return $this->createGetDoctorDto($doctor);
    }


    public function findDoctorsBySpecialization(int $specializationId): array
    {
        $specialization = $this->specializationRepository->find($specializationId);
        if (!$specialization)
            $this->apiException->exception("This Specialization doesn't exist", 422);

        $doctors = $specialization->getDoctors();
        $doctorsDTOs = [];
        foreach ($doctors as $doctor) {
            $doctorsDTOs[] = $this->createGetDoctorDto($doctor);
        }
        return $doctorsDTOs;
    }


    public function updateSpecialization(UpdateDoctorSpecializationDto $dto, int $doctorId, bool $delete = false): GetDoctorDto
    {
        $specialization = $this->specializationRepository->find($dto->getSpecializationId());
        if (!$specialization)
            $this->apiException->exception("This Specialization doesn't exist", 422);

        $doctor = $this->doctorRepository->find($doctorId);
        if (!$doctor)
            $this->apiException->exception("This Doctor doesn't exist", 422);

        $doctorSpecializations = $doctor->getSpecializations();

        if(!$delete) {
            if($doctorSpecializations->contains($specialization))
                $this->apiException->exception("This specialty has already been added", 422);

            $doctor->addSpecialization($specialization);
        }
        if ($delete) {
            if(!$doctorSpecializations->contains($specialization))
                $this->apiException->exception("The doctor does not have this specialization", 422);

            $doctor->removeSpecialization($specialization);
        }
        $this->doctorRepository->save($doctor, true);
        return $this->createGetDoctorDto($doctor);
    }


    private function createGetDoctorDto(Doctor $doctor): GetDoctorDto
    {
        $dto = new GetDoctorDto();
        $dto->setId($doctor->getId());
        $dto->setFirstName($doctor->getFirstName());
        $dto->setLastName($doctor->getLastName());
        $dto->setEmail($doctor->getAuthUser()->getEmail());
        $dto->setPhone($doctor->getPhone());

        $specializations = [];
        foreach ($doctor->getSpecializations() as $specialization) {
            $specializations[] = $specialization->getName();
        }
        $dto->setSpecialization($specializations);

        return $dto;
    }
}