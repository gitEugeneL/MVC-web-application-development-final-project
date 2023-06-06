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
