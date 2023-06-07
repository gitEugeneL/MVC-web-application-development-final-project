<?php

namespace App\Entity;

use App\Repository\DoctorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DoctorRepository::class)]
class Doctor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $firstName = null;

    #[ORM\Column(length: 100)]
    private ?string $lastName = null;

    #[ORM\Column(length: 12)]
    private ?string $phone = null;

    #[ORM\ManyToMany(targetEntity: Specialization::class, inversedBy: 'doctors')]
    #[ORM\JoinTable(name: 'doctor_specialization')]
    private ?Collection $specializations = null;

    #[ORM\OneToMany(mappedBy: 'doctor', targetEntity: Visit::class)]
    private Collection $visits;

    #[ORM\OneToMany(mappedBy: 'doctor', targetEntity: MedicalRecord::class)]
    private Collection $medicalRecords;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?User $authUser = null;


    public function __construct()
    {
        $this->specializations = new ArrayCollection();
        $this->visits = new ArrayCollection();
        $this->medicalRecords = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, Specialization>
     */
    public function getSpecializations(): Collection
    {
        return $this->specializations;
    }

    public function addSpecialization(Specialization $specialization): self
    {
        if (!$this->specializations->contains($specialization)) {
            $this->specializations->add($specialization);
            $specialization->addDoctor($this);
        }
        return $this;
    }

    public function removeSpecialization(Specialization $specialization): self
    {
        if ($this->specializations->removeElement($specialization)) {
            $specialization->removeDoctor($this);
        }
        return $this;
    }

    /**
     * @return Collection<int, Visit>
     */
    public function getVisits(): Collection
    {
        return $this->visits;
    }

    public function addVisit(Visit $visit): self
    {
        if (!$this->visits->contains($visit)) {
            $this->visits->add($visit);
            $visit->setDoctor($this);
        }
        return $this;
    }

    public function removeVisit(Visit $visit): self
    {
        if ($this->visits->removeElement($visit)) {
            // set the owning side to null (unless already changed)
            if ($visit->getDoctor() === $this) {
                $visit->setDoctor(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection<int, MedicalRecord>
     */
    public function getMedicalRecords(): Collection
    {
        return $this->medicalRecords;
    }

    public function addMedicalRecord(MedicalRecord $medicalRecord): self
    {
        if (!$this->medicalRecords->contains($medicalRecord)) {
            $this->medicalRecords->add($medicalRecord);
            $medicalRecord->setDoctor($this);
        }
        return $this;
    }

    public function removeMedicalRecord(MedicalRecord $medicalRecord): self
    {
        if ($this->medicalRecords->removeElement($medicalRecord)) {
            // set the owning side to null (unless already changed)
            if ($medicalRecord->getDoctor() === $this) {
                $medicalRecord->setDoctor(null);
            }
        }
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
