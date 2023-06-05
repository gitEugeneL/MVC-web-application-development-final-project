<?php

namespace App\Service;

use App\Doctor\CreateDoctorDto;
use App\Doctor\GetDoctorDto;
use App\Entity\Doctor;
use App\Entity\User;
use App\Exception\ApiException;
use App\Repository\DoctorRepository;
use App\Repository\SpecializationRepository;
use App\Repository\UserRepository;

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

        $specialization = $this->specializationRepository->find($dto->getSpecializationId());
        if (!$specialization)
            $this->apiException->exception("This specialization doesn't exist", 422);

        $doctor = (new Doctor())
            ->setFirstName($dto->getFirstName())
            ->setLastName($dto->getLastName())
            ->setPhone($dto->getPhone())
            ->setSpecializations($specialization)
            ->setAuthUser((new User())
                ->setEmail($dto->getEmail())
                ->setPassword(password_hash($dto->getPassword(), PASSWORD_DEFAULT))
                ->setRoles(["ROLE_DOCTOR"]));

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


    private function createGetDoctorDto(Doctor $doctor): GetDoctorDto
    {
        $dto = new GetDoctorDto();
        $dto->setId($doctor->getId());
        $dto->setFirstName($doctor->getFirstName());
        $dto->setLastName($doctor->getLastName());
        $dto->setEmail($doctor->getAuthUser()->getEmail());
        $dto->setPhone($doctor->getPhone());
        $dto->setSpecialization($doctor->getSpecializations()->getName());
        return $dto;
    }

}