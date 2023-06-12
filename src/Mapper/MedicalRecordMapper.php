<?php

namespace App\Mapper;

use App\Entity\MedicalRecord;
use App\MedicalRecord\GetMedicalRecordDto;

class MedicalRecordMapper
{
    public function createGetMedicalRecordDto(MedicalRecord $medicalRecord): GetMedicalRecordDto
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