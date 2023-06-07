<?php

namespace App\MedicalRecord;

use Symfony\Component\Validator\Constraints as Assert;

class CreateMedicalRecordDto
{
    #[Assert\NotNull]
    private string $name;

    #[Assert\NotNull]
    private string $description;

    #[Assert\NotNull]
    private int $patientId;

    #[Assert\NotNull]
    private int $visitId;


    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return int
     */
    public function getPatientId(): int
    {
        return $this->patientId;
    }

    /**
     * @param int $patientId
     */
    public function setPatientId(int $patientId): void
    {
        $this->patientId = $patientId;
    }

    /**
     * @return int
     */
    public function getVisitId(): int
    {
        return $this->visitId;
    }

    /**
     * @param int $visitId
     */
    public function setVisitId(int $visitId): void
    {
        $this->visitId = $visitId;
    }
}
