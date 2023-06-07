<?php

namespace App\Doctor;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateDoctorSpecializationDto
{
    #[Assert\NotBlank]
    private int $specializationId;

    /**
     * @return int
     */
    public function getSpecializationId(): int
    {
        return $this->specializationId;
    }

    /**
     * @param int $specializationId
     */
    public function setSpecializationId(int $specializationId): void
    {
        $this->specializationId = $specializationId;
    }
}