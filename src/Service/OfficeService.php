<?php

namespace App\Service;

use App\Entity\Office;
use App\Exception\ApiException;
use App\Mapper\OfficeMapper;
use App\Office\CreateOfficeDto;
use App\Office\GetOfficeDto;
use App\Repository\OfficeRepository;

class OfficeService
{
    private OfficeRepository $officeRepository;
    private ApiException $apiException;
    private OfficeMapper $officeMapper;
    public function __construct(OfficeRepository $officeRepository,
                                ApiException $apiException, OfficeMapper $officeMapper)
    {
        $this->officeRepository = $officeRepository;
        $this->apiException = $apiException;
        $this->officeMapper = $officeMapper;
    }


    public function createOffice(CreateOfficeDto $dto): GetOfficeDto
    {
        if($this->officeRepository->findOneBy(["number" => $dto->getNumber()]) !== null) {
            $this->apiException->exception("This office already exist", 422);
        }
        $office = new Office();
        $office->setName($dto->getName());
        $office->setNumber($dto->getNumber());
        $office->setIsAvailable(false);

        $this->officeRepository->save($office, true);
        return $this->officeMapper->createGetOfficeDto($office);
    }


    public function showOffice(): array
    {
        $offices = $this->officeRepository->findAll();
        $officesDTOs = [];

        foreach ($offices as $office) {
            $officesDTOs[] = $this->officeMapper->createGetOfficeDto($office);
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
        return $this->officeMapper->createGetOfficeDto($office);
    }
}