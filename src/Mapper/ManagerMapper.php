<?php

namespace App\Mapper;

use App\Dto\GetManagerDto;
use App\Entity\Manager;

class ManagerMapper
{
    public function createManagerDto(Manager $manager): GetManagerDto
    {
        $dto = new GetManagerDto();
        $dto->setId($manager->getId());
        $dto->setFirstName($manager->getFirstName());
        $dto->setLastName($manager->getLastName());
        $dto->setPhone($manager->getPhone());
        $dto->setEmail($manager->getAuthUser()->getEmail());
        return $dto;
    }
}