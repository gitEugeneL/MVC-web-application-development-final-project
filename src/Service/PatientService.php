<?php

namespace App\Service;

use App\Entity\Patient;
use App\Entity\User;
use App\Exception\ApiException;
use App\Patient\CreatePatientDto;
use App\Patient\GetPatientDto;
use App\Repository\PatientRepository;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\User\UserInterface;


class PatientService
{
    private UserRepository $userRepository;
    private PatientRepository $patientRepository;
    private ApiException $apiException;
    public function __construct(UserRepository $userRepository, PatientRepository $patientRepository,
                                ApiException $apiException)
    {
        $this->patientRepository = $patientRepository;
        $this->userRepository = $userRepository;
        $this->apiException = $apiException;
    }

    public function createPatient(CreatePatientDto $dto): GetPatientDto
    {
        if ($this->userRepository->findOneBy(['email' => $dto->getEmail()]) !== null)
            $this->apiException->exception("This Patient already exist", 422);

        $patient = (new Patient())
            ->setFirstName($dto->getFirstName())
            ->setLastName($dto->getLastName())
            ->setDateOfBirth($dto->getDateOfBirth())
            ->setPhone($dto->getPhone())
            ->setInsurance($dto->getInsurance())
            ->setAuthUser((new User())
                ->setEmail($dto->getEmail())
                ->setPassword(password_hash($dto->getPassword(), PASSWORD_DEFAULT))
                ->setRoles(["ROLE_PATIENT"]));

        $patient->setPesel($dto->getPesel());

        $this->patientRepository->save($patient, true);
        return $this->createGetPatientDto($patient);
    }

    public function showPatients(): array
    {
        $patients = $this->patientRepository->findAll();
        $patientsDTOs = [];
        foreach ($patients as $patient) {
            $patientsDTOs[] = $this->createGetPatientDto($patient);
        }
        return $patientsDTOs;
    }

    public function getAuthPatient(UserInterface $authUser): GetPatientDto
    {
        $patient = $this->patientRepository
            ->findOneBy(["authUser" => $this->userRepository
                ->findOneBy(['email' => $authUser->getUserIdentifier()])]);

        if (!$patient)
            $this->apiException->exception("This Patient doesn't exist", 422);

        return $this->createGetPatientDto($patient);
    }


    private function createGetPatientDto(Patient $patient): GetPatientDto
    {
        $dto = new GetPatientDto();
        $dto->setId($patient->getId());
        $dto->setFirstName($patient->getFirstName());
        $dto->setLastName($patient->getLastName());
        $dto->setDateOfBirth($patient->getDateOfBirth());
        $dto->setPesel($patient->getPesel());
        $dto->setPhone($patient->getPhone());
        $dto->setInsurance($patient->getInsurance());
        $dto->setEmail($patient->getAuthUser()->getEmail());
        return $dto;
    }
}