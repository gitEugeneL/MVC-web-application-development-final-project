<?php

namespace App\Mapper;

use App\Doctor\GetDoctorDto;
use App\Entity\Doctor;

class DoctorMapper
{
    public function createGetDoctorDto(Doctor $doctor) : GetDoctorDto
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