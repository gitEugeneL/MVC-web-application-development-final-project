<?php

namespace App\Service;

use App\Entity\Specialization;
use App\Exception\ApiException;
use App\Repository\SpecializationRepository;
use App\Specialization\CreateSpecializationDto;
use App\Specialization\GetSpecializationDto;

class SpecializationService
{
    private SpecializationRepository $specializationRepository;
    private ApiException $apiException;
    public function __construct(SpecializationRepository $specializationRepository, ApiException $apiException)
    {
        $this->specializationRepository = $specializationRepository;
        $this->apiException = $apiException;
    }


    public function createSpecialization(CreateSpecializationDto $dto): GetSpecializationDto
    {
        if ($this->specializationRepository->findOneBy(["name" => $dto->getName()]) !== null)
        {
            $this->apiException->exception("This specialization already exist", 422);
        }
        $specialization = (new Specialization())
            ->setName($dto->getName());

        $this->specializationRepository->save($specialization, true);

        return $this->createGetSpecializationDto($specialization);
    }


    public function showSpecializations(): array
    {
        $specializations = $this->specializationRepository->findAll();
        $specializationDTOs = [];

        foreach ($specializations as $specialization) {
            $specializationDTOs[] = $this->createGetSpecializationDto($specialization);
        }
        return $specializationDTOs;
    }


    private function createGetSpecializationDto(Specialization $specialization): GetSpecializationDto
    {
        $dto = new GetSpecializationDto();
        $dto->setId($specialization->getId());
        $dto->setName($specialization->getName());
        return $dto;
    }




}