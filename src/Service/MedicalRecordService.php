<?php

namespace App\Service;

use App\Entity\MedicalRecord;
use App\Exception\ApiException;
use App\Mapper\MedicalRecordMapper;
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
    private MedicalRecordMapper $medicalRecordMapper;
    public function __construct(PatientRepository $patientRepository, VisitRepository $visitRepository,
                                UserRepository $userRepository, DoctorRepository $doctorRepository,
                                MedicalRecordRepository $medicalRecordRepository, MedicalRecordMapper $medicalRecordMapper,
                                ApiException $apiException)
    {
        $this->visitRepository = $visitRepository;
        $this->patientRepository = $patientRepository;
        $this->userRepository = $userRepository;
        $this->doctorRepository = $doctorRepository;
        $this->medicalRecordRepository = $medicalRecordRepository;
        $this->apiException = $apiException;
        $this->medicalRecordMapper = $medicalRecordMapper;
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

        return $this->medicalRecordMapper->createGetMedicalRecordDto($medicalRecord);
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
            $medicalRecordsDTOs[] = $this->medicalRecordMapper->createGetMedicalRecordDto($record);
        }
        return $medicalRecordsDTOs;
    }
}

