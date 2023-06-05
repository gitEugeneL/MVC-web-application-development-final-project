<?php

namespace App\Doctor;

use Symfony\Component\Validator\Constraints as Assert;

class CreateDoctorDto
{
    #[Assert\NotBlank]
    private string $firstName;

    #[Assert\NotBlank]
    private string $lastName;

    #[Assert\NotBlank]
    private string $phone;

    #[Assert\NotBlank]
    private int $specializationId;

    #[Assert\Length(min: 4)]
    #[Assert\NotNull]
    private string $password;

    #[Assert\NotBlank]
    #[Assert\Email]
    private string $email;


    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

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

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }
}