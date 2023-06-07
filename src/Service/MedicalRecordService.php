<?php

namespace App\Service;

use App\Entity\MedicalRecord;
use App\Exception\ApiException;
use App\MedicalRecord\CreateMedicalRecordDto;
use App\MedicalRecord\GetMedicalRecordDto;
use App\Repository\DoctorRepository;
use App\Repository\MedicalRecordRepository;
use App\Repository\PatientRepository;
use App\Repository\UserRepository;
use App\Repository\VisitRepository;
use Symfony\Component\Security\Core\User\UserInterface;

class MedicalRecordService
{
    private MedicalRecordRepository $medicalRecordRepository;
    private DoctorRepository $doctorRepository;
    private UserRepository $userRepository;
    private PatientRepository $patientRepository;
    private VisitRepository $visitRepository;
    private ApiException $apiException;
    public function __construct(PatientRepository $patientRepository, VisitRepository $visitRepository,
                                UserRepository $userRepository, DoctorRepository $doctorRepository,
                                MedicalRecordRepository $medicalRecordRepository, ApiException $apiException)
    {
        $this->visitRepository = $visitRepository;
        $this->patientRepository = $patientRepository;
        $this->userRepository = $userRepository;
        $this->doctorRepository = $doctorRepository;
        $this->medicalRecordRepository = $medicalRecordRepository;
        $this->apiException = $apiException;
    }


    public function createMedicalService(CreateMedicalRecordDto $dto, UserInterface $authDoctor): GetMedicalRecordDto
    {
        $doctor = $this->doctorRepository
            ->findOneBy(["authUser" => $this->userRepository
                ->findOneBy(['email' => $authDoctor->getUserIdentifier()])]);
        if(!$doctor)
            $this->apiException->exception("This Doctor doesn't exist", 422);

        $patient = $this->patientRepository->find($dto->getPatientId());
        if (!$patient)
            $this->apiException->exception("This Patient doesn't exist", 422);

        $visit = $this->visitRepository->find($dto->getVisitId());
        if(!$visit
            || $visit->getPatient()->getId() !== $dto->getPatientId() || $visit->getDoctor()->getId() !== $doctor->getId()) {
                $this->apiException->exception("This Visit doesn't exist", 422);
        }

        $medicalRecord = $this->medicalRecordRepository->findBy(["visit" => $visit]);
        if ($medicalRecord)
            $this->apiException->exception("This Medical Record already exist", 422);

        $medicalRecord = (new MedicalRecord())
            ->setName($dto->getName())
            ->setDescription($dto->getDescription())
            ->setDoctor($doctor)
            ->setPatient($patient)
            ->setVisit($visit);

        $this->medicalRecordRepository->save($medicalRecord, true);

        return $this->createGetMedicalRecordDto($medicalRecord);
    }


    public function findPatientRecords(UserInterface $authPatient): array
    {
        $patient = $this->patientRepository
            ->findOneBy(["authUser" => $this->userRepository
                ->findOneBy(['email' => $authPatient->getUserIdentifier()])]);
        if(!$patient)
            $this->apiException->exception("This Patient doesn't exist", 422);

        $medicalRecords = $this->medicalRecordRepository->findBy(["patient" => $patient]);

        $medicalRecordsDTOs = [];
        foreach ($medicalRecords as $record) {
            $medicalRecordsDTOs[] = $this->createGetMedicalRecordDto($record);
        }
        return $medicalRecordsDTOs;
    }


    private function createGetMedicalRecordDto(MedicalRecord $medicalRecord): GetMedicalRecordDto
    {
        $doctor = $medicalRecord->getDoctor();
        $patient = $medicalRecord->getPatient();
        $visit = $medicalRecord->getVisit();

        $dto = new GetMedicalRecordDto();
        $dto->setId($medicalRecord->getId());
        $dto->setName($medicalRecord->getName());
        $dto->setDescription($medicalRecord->getDescription());
        $dto->setDate($visit->getDate()->format('Y-m-d'));
        $dto->setStartTime($visit->getStartTime()->format('H:i'));
        $dto->setEndTime($visit->getEndTime()->format('H:i'));
        $dto->setPatientId($patient->getId());
        $dto->setDoctorId($doctor->getId());
        $dto->setPatientFirstName($patient->getFirstName());
        $dto->setPatientLastName($patient->getLastName());
        $dto->setDoctorFirstName($doctor->getFirstName());
        $dto->setDoctorLastName($doctor->getLastName());
        return $dto;
    }
}

