<?php

namespace App\Visit;

class GetVisitDto
{
    private int $id;
    private string $day;
    private string $startTime;
    private string $endTime;
    private int $patientId;
    private int $doctorId;
    private string $patientFirstName;
    private string $patientLastName;
    private string $patientEmail;
    private string $patientInsurance;
    private string $patientPhone;
    private string $patientPesel;
    private string $doctorFirstName;
    private string $doctorLastName;
    private string $doctorEmail;
    private string $doctorPhone;
    private bool $completed;

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
    public function getDoctorEmail(): string
    {
        return $this->doctorEmail;
    }

    /**
     * @param string $doctorEmail
     */
    public function setDoctorEmail(string $doctorEmail): void
    {
        $this->doctorEmail = $doctorEmail;
    }

    /**
     * @return string
     */
    public function getDoctorPhone(): string
    {
        return $this->doctorPhone;
    }

    /**
     * @param string $doctorPhone
     */
    public function setDoctorPhone(string $doctorPhone): void
    {
        $this->doctorPhone = $doctorPhone;
    }

    /**
     * @return bool
     */
    public function isCompleted(): bool
    {
        return $this->completed;
    }

    /**
     * @param bool $completed
     */
    public function setCompleted(bool $completed): void
    {
        $this->completed = $completed;
    }

    /**
     * @return string
     */
    public function getPatientPhone(): string
    {
        return $this->patientPhone;
    }

    /**
     * @param string $patientPhone
     */
    public function setPatientPhone(string $patientPhone): void
    {
        $this->patientPhone = $patientPhone;
    }

    /**
     * @return string
     */
    public function getPatientPesel(): string
    {
        return $this->patientPesel;
    }

    /**
     * @param string $patientPesel
     */
    public function setPatientPesel(string $patientPesel): void
    {
        $this->patientPesel = $patientPesel;
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
    public function getPatientEmail(): string
    {
        return $this->patientEmail;
    }

    /**
     * @param string $patientEmail
     */
    public function setPatientEmail(string $patientEmail): void
    {
        $this->patientEmail = $patientEmail;
    }

    /**
     * @return string
     */
    public function getPatientInsurance(): string
    {
        return $this->patientInsurance;
    }

    /**
     * @param string $patientInsurance
     */
    public function setPatientInsurance(string $patientInsurance): void
    {
        $this->patientInsurance = $patientInsurance;
    }

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
     * @return string
     */
    public function getDay(): string
    {
        return $this->day;
    }

    /**
     * @param string $day
     */
    public function setDay(string $day): void
    {
        $this->day = $day;
    }

    /**
     * @return string
     */
    public function getStartTime(): string
    {
        return $this->startTime;
    }

    /**
     * @param string $startLooking for inspiration and ideas for growing your shop?

We covered it at Etsy Up! Watch the recording to see what you missed or revisit your favorite sessions.Time
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
}
