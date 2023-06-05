<?php

namespace App\Service;

use App\Entity\Office;
use App\Exception\ApiException;
use App\Office\CreateOfficeDto;
use App\Office\GetOfficeDto;
use App\Repository\OfficeRepository;

class OfficeService
{
    private OfficeRepository $officeRepository;
    private ApiException $apiException;
    public function __construct(OfficeRepository $officeRepository, ApiException $apiException)
    {
        $this->officeRepository = $officeRepository;
        $this->apiException = $apiException;
    }


    public function createOffice(CreateOfficeDto $dto): GetOfficeDto
    {
        if($this->officeRepository->findOneBy(["number" => $dto->getNumber()]) !== null) {
            $this->apiException->exception("This office already exist", 422);
        }
        $office = new Office();
        $office->setName($dto->getName());
        $office->setNumber($dto->getNumber());
        $office->setIsAvailable($dto->isAvailable());

        $this->officeRepository->save($office, true);
        return $this->createGetOfficeDto($office);
    }


    public function showOffice(): array
    {
        $offices = $this->officeRepository->findAll();
        $officesDTOs = [];

        foreach ($offices as $office) {
            $officesDTOs[] = $this->createGetOfficeDto($office);
        }
        return $officesDTOs;
    }


    public function updateOffice(int $id): GetOfficeDto
    {
        $office = $this->officeRepository->find($id);
        if($office === null)
            $this->apiException->exception("This office doesn't exist", 422);

        $office->setIsAvailable(!$office->getIsAvailable());
        $this->officeRepository->save($office, true);
        return $this->createGetOfficeDto($office);
    }







    private function createGetOfficeDto(Office $office): GetOfficeDto
    {
        $dto = new GetOfficeDto();
        $dto->setId($office->getId());
        $dto->setName($office->getName());
        $dto->setNumber($office->getNumber());
        $dto->setIsAvailable($office->getIsAvailable());
        return $dto;
    }
}