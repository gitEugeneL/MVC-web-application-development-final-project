<?php

namespace App\Office;

use Symfony\Component\Validator\Constraints as Assert;

class CreateOfficeDto
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 5)]
    private string $name;

    #[Assert\NotBlank]
    private int $number;

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
     * @return int
     */
    public function getNumber(): int
    {
        return $this->number;
    }

    /**
     * @param int $number
     */
    public function setNumber(int $number): void
    {
        $this->number = $number;
    }
}