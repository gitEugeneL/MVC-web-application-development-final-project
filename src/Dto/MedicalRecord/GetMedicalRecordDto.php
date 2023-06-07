<?php

namespace App\MedicalRecord;

use App\Doctor\GetDoctorDto;
use App\Patient\GetPatientDto;
use PhpParser\Node\Scalar\String_;

class GetMedicalRecordDto
{
    private int $id;
    private int $patientId;
    private int $doctorId;
    private string $patientLastName;
    private string $patientFirstName;

    private string $doctorLastName;
    private string $doctorFirstName;

    private string $name;
    private string $description;
    private string $date;
    private string $startTime;
    private string $endTime;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
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
    public function getDoctorId(): int
    {
        return $this->doctorId;
    }

    /**
     * @param int $doctorId
     */
    public function setDoctorId(int $doctorId): void
    {
        $this->doctorId = $doctorId;
    }

    /**
     * @return string
     */
    public function getPatientLastName(): string
    {
        return $this->patientLastName;
    }

    /**
     * @param string $patientLastName
     */
    public function setPatientLastName(string $patientLastName): void
    {
        $this->patientLastName = $patientLastName;
    }

    /**
     * @return string
     */
    public function getPatientFirstName(): string
    {
        return $this->patientFirstName;
    }

    /**
     * @param string $patientFirstName
     */
    public function setPatientFirstName(string $patientFirstName): void
    {
        $this->patientFirstName = $patientFirstName;
    }

    /**
     * @return string
     */
    public function getDoctorLastName(): string
    {
        return $this->doctorLastName;
    }

    /**
     * @param string $doctorLastName
     */
    public function setDoctorLastName(string $doctorLastName): void
    {
        $this->doctorLastName = $doctorLastName;
    }

    /**
     * @return string
     */
    public function getDoctorFirstName(): string
    {
        return $this->doctorFirstName;
    }

    /**
     * @param string $doctorFirstName
     */
    public function setDoctorFirstName(string $doctorFirstName): void
    {
        $this->doctorFirstName = $doctorFirstName;
    }

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
     * @return string
     */
    public function getDate(): string
    {
        return $this->date;
    }

    /**
     * @param string $date
     */
    public function setDate(string $date): void
    {
        $this->date = $date;
    }

    /**
     * @return string
     */
    public function getStartTime(): string
    {
        return $this->startTime;
    }

    /**
     * @param string $startTime
     */
    public function setStartTime(string $startTime): void
    {
        $this->startTime = $startTime;
    }

    /**
     * @return string
     */
    public function getEndTime(): string
    {
        return $this->endTime;
    }

    /**
     * @param string $endTime
     */
    public function setEndTime(string $endTime): void
    {
        $this->endTime = $endTime;
    }
}