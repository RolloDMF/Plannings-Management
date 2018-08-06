<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ScheduleRepository")
 */
class Schedule
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Day")
     * @ORM\JoinColumn(nullable=false)
     */
    private $day;

    /**
     * @ORM\Column(type="time")
     */
    private $firstTimeStart;

    /**
     * @ORM\Column(type="time")
     */
    private $firstTimeStop;

    /**
     * @ORM\Column(type="time")
     */
    private $secondTimeStart;

    /**
     * @ORM\Column(type="time")
     */
    private $secondTimeStop;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Company", inversedBy="schedules")
     * @ORM\JoinColumn(nullable=false)
     */
    private $company;

    public function getId()
    {
        return $this->id;
    }

    public function getDay(): ?Day
    {
        return $this->day;
    }

    public function setDay(?Day $day): self
    {
        $this->day = $day;

        return $this;
    }

    public function getFirstTimeStart(): ?\DateTimeInterface
    {
        return $this->firstTimeStart;
    }

    public function setFirstTimeStart(\DateTimeInterface $firstTimeStart): self
    {
        $this->firstTimeStart = $firstTimeStart;

        return $this;
    }

    public function getFirstTimeStop(): ?\DateTimeInterface
    {
        return $this->firstTimeStop;
    }

    public function setFirstTimeStop(\DateTimeInterface $firstTimeStop): self
    {
        $this->firstTimeStop = $firstTimeStop;

        return $this;
    }

    public function getSecondTimeStart(): ?\DateTimeInterface
    {
        return $this->secondTimeStart;
    }

    public function setSecondTimeStart(\DateTimeInterface $secondTimeStart): self
    {
        $this->secondTimeStart = $secondTimeStart;

        return $this;
    }

    public function getSecondTimeStop(): ?\DateTimeInterface
    {
        return $this->secondTimeStop;
    }

    public function setSecondTimeStop(\DateTimeInterface $secondTimeStop): self
    {
        $this->secondTimeStop = $secondTimeStop;

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
}
