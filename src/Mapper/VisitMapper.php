<?php

namespace App\Mapper;

use App\Entity\Visit;
use App\Visit\GetVisitDto;

class VisitMapper
{
    public function createGetVisitDto(Visit $visit): GetVisitDto
    {
        $day = $visit->getDate()->format('Y-m-d');
        $startTIme = $visit->getStartTime()->format('H:i');
        $endTime = $visit->getEndTime()->format('H:i');
        $patient = $visit->getPatient();
        $doctor = $visit->getDoctor();

        $dto = new GetVisitDto();
        $dto->setId($visit->getId());
        $dto->setDay($day);
        $dto->setStartTime($startTIme);
        $dto->setEndTime($endTime);
        $dto->setPatientId($patient->getId());
        $dto->setDoctorId($visit->getDoctor()->getId());
        $dto->setPatientFirstName($patient->getFirstName());
        $dto->setPatientLastName($patient->getLastName());
        $dto->setPatientEmail($patient->getAuthUser()->getEmail());
        $dto->setPatientInsurance($patient->getInsurance());
        $dto->setPatientPhone($patient->getPhone());
        $dto->setPatientPesel($patient->getPesel());
        $dto->setCompleted($visit->getCompleted());
        $dto->setDoctorFirstName($doctor->getFirstName());
        $dto->setDoctorLastName($doctor->getLastName());
        $dto->setDoctorEmail($doctor->getAuthUser()->getEmail());
        $dto->setDoctorPhone($doctor->getPhone());
        return $dto;
    }
}