<?php

namespace App\Mapper;

use App\Entity\Specialization;
use App\Specialization\GetSpecializationDto;

class SpecializationMapper
{
    public function createGetSpecializationDto(Specialization $specialization): GetSpecializationDto
    {
        $dto = new GetSpecializationDto();
        $dto->setId($specialization->getId());
        $dto->setName($specialization->getName());
        return $dto;
    }
}