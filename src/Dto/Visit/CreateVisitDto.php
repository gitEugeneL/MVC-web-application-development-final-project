<?php

namespace App\Visit;

use Symfony\Component\Validator\Constraints as Assert;


class CreateVisitDto
{
    #[Assert\NotNull]
    private int $doctorId;

    #[Assert\NotNull]
    private \DateTimeImmutable $day;

    #[Assert\NotNull]
    private \DateTimeInterface $startTime;

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
     * @return \DateTimeImmutable
     */
    public function getDay(): \DateTimeImmutable
    {
        return $this->day;
    }

    /**
     * @param \DateTimeImmutable $day
     */
    public function setDay(\DateTimeImmutable $day): void
    {
        $this->day = $day;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getStartTime(): \DateTimeInterface
    {
        return $this->startTime;
    }

    /**
     * @param \DateTimeInterface $startTime
     */
    public function setStartTime(\DateTimeInterface $startTime): void
    {
        $this->startTime = $startTime;
    }
}