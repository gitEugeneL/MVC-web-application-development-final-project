<?php

namespace App\Service;

use App\Entity\Patient;
use App\Entity\User;
use App\Exception\ApiException;
use App\Mapper\PatientMapper;
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
    private PatientMapper $patientMapper;
    public function __construct(UserRepository $userRepository, PatientRepository $patientRepository,
                                PatientMapper $patientMapper, ApiException $apiException)
    {
        $this->patientRepository = $patientRepository;
        $this->userRepository = $userRepository;
        $this->apiException = $apiException;
        $this->patientMapper = $patientMapper;
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
        return $this->patientMapper->createGetPatientDto($patient);
    }

    public function showPatients(): array
    {
        $patients = $this->patientRepository->findAll();
        $patientsDTOs = [];
        foreach ($patients as $patient) {
            $patientsDTOs[] = $this->patientMapper->createGetPatientDto($patient);
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

        return $this->patientMapper->createGetPatientDto($patient);
    }
}