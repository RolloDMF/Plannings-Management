<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EmployeeRepository")
 */
class Employee
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2)
     */
    private $numberOfHours;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2)
     */
    private $remainingLeave;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Planning", mappedBy="employee", orphanRemoval=true)
     */
    private $plannings;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Company", inversedBy="employees")
     * @ORM\JoinColumn(nullable=false)
     */
    private $company;

    /**
     * @ORM\Column(type="string", length=7)
     */
    private $color;

    public function __construct()
    {
        $this->plannings = new ArrayCollection();
    }

    public function __toString()
    {
       return $this->getFirstName();   
    }

    public function getId()
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getNumberOfHours()
    {
        return $this->numberOfHours;
    }

    public function setNumberOfHours($numberOfHours): self
    {
        $this->numberOfHours = $numberOfHours;

        return $this;
    }

    public function getRemainingLeave()
    {
        return $this->remainingLeave;
    }

    public function setRemainingLeave($remainingLeave): self
    {
        $this->remainingLeave = $remainingLeave;

        return $this;
    }

    /**
     * @return Collection|Planning[]
     */
    public function getPlannings(): Collection
    {
        return $this->plannings;
    }

    public function addPlanning(Planning $planning): self
    {
        if (!$this->plannings->contains($planning)) {
            $this->plannings[] = $planning;
            $planning->setEmployee($this);
        }

        return $this;
    }

    public function removePlanning(Planning $planning): self
    {
        if ($this->plannings->contains($planning)) {
            $this->plannings->removeElement($planning);
            // set the owning side to null (unless already changed)
            if ($planning->getEmployee() === $this) {
                $planning->setEmployee(null);
            }
        }

        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }
}
