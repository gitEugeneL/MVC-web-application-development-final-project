<?php

namespace App\Mapper;

use App\Entity\Patient;
use App\Patient\GetPatientDto;

class PatientMapper
{
    public function createGetPatientDto(Patient $patient): GetPatientDto
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