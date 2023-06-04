<?php

namespace App\Entity;

use App\Repository\ManagerRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: ManagerRepository::class)]
class Manager
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[ORM\Column(length: 50)]
    private ?string $firstName = null;

    #[Assert\NotBlank]
    #[ORM\Column(length: 100)]
    private ?string $lastName = null;

    #[Assert\NotBlank]
    #[ORM\Column(length: 12)]
    private ?string $phone = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?User $authUser = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;
        return $this;
    }

    public function getAuthUser(): ?User
    {
        return $this->authUser;
    }

    public function setAuthUser(?User $authUser): self
    {
        $this->authUser = $authUser;
        return $this;
    }
}
