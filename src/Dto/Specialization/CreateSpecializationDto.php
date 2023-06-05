<?php

namespace App\Specialization;

use Symfony\Component\Validator\Constraints as Assert;


class CreateSpecializationDto
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 5)]
    private string $name;


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
}