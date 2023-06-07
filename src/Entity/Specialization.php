<?php

namespace App\Entity;

use App\Repository\SpecializationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SpecializationRepository::class)]
class Specialization
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: Doctor::class, mappedBy: 'specializations')]
    private Collection $doctors;


    public function __construct()
    {
        $this->doctors = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return Collection<int, Doctor>
     */
    public function getDoctors(): Collection
    {
        return $this->doctors;
    }

    public function addDoctor(Doctor $doctor): self
    {
        if (!$this->doctors->contains($doctor)) {
            $this->doctors->add($doctor);
            $doctor->addSpecialization($this);
        }
        return $this;
    }

    public function removeDoctor(Doctor $doctor): self
    {
        if ($this->doctors->removeElement($doctor)) {
            $doctor->removeSpecialization($this);
        }
        return $this;
    }
}
