<?php

namespace App\Service;

use App\Entity\Specialization;
use App\Exception\ApiException;
use App\Mapper\SpecializationMapper;
use App\Repository\SpecializationRepository;
use App\Specialization\CreateSpecializationDto;
use App\Specialization\GetSpecializationDto;

class SpecializationService
{
    private SpecializationRepository $specializationRepository;
    private ApiException $apiException;
    private SpecializationMapper $specializationMapper;
    public function __construct(SpecializationRepository $specializationRepository,
                                SpecializationMapper $specializationMapper, ApiException $apiException)
    {
        $this->specializationRepository = $specializationRepository;
        $this->apiException = $apiException;
        $this->specializationMapper = $specializationMapper;
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

        return $this->specializationMapper->createGetSpecializationDto($specialization);
    }


    public function showSpecializations(): array
    {
        $specializations = $this->specializationRepository->findAll();
        $specializationDTOs = [];

        foreach ($specializations as $specialization) {
            $specializationDTOs[] = $this->specializationMapper->createGetSpecializationDto($specialization);
        }
        return $specializationDTOs;
    }
}