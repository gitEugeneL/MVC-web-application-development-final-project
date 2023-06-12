<?php

namespace App\Mapper;

use App\Entity\Office;
use App\Office\GetOfficeDto;

class OfficeMapper
{
    public function createGetOfficeDto(Office $office): GetOfficeDto
    {
        $dto = new GetOfficeDto();
        $dto->setId($office->getId());
        $dto->setName($office->getName());
        $dto->setNumber($office->getNumber());
        $dto->setIsAvailable($office->getIsAvailable());
        return $dto;
    }
}