<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Service\ConverterController;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PlanningRepository")
 */
class Planning
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $dayDate;

    /**
     * @ORM\Column(type="time")
     */
    private $startTime;

    /**
     * @ORM\Column(type="time")
     */
    private $stopTime;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Day", inversedBy="plannings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $day;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Employee", inversedBy="plannings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $employee;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Company", inversedBy="plannings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $company;

    /**
     * @ORM\Column(type="smallint")
     */
    private $week;

    /**
     * @ORM\Column(type="smallint")
     */
    private $year;

    /**
     * @ORM\Column(type="decimal", precision=4, scale=2,, nullable=true)
     */
    private $convertedStartTime;

    /**
     * @ORM\Column(type="decimal", precision=4, scale=2,, nullable=true)
     */
    private $convertedStopTime;

    public function getId()
    {
        return $this->id;
    }

    public function getDayDate(): ?\DateTimeInterface
    {
        return $this->dayDate;
    }

    public function setDayDate(\DateTimeInterface $dayDate): self
    {
        $this->dayDate = $dayDate;

        return $this;
    }

    public function getStartTime(): ?\DateTimeInterface
    {
        return $this->startTime;
    }

    public function setStartTime(\DateTimeInterface $startTime): self
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getStopTime(): ?\DateTimeInterface
    {
        return $this->stopTime;
    }

    public function setStopTime(\DateTimeInterface $stopTime): self
    {
        $this->stopTime = $stopTime;

        return $this;
    }

    public function getDay()
    {
        return $this->day;
    }

    public function setDay($day)
    {
        $this->day = $day;

        return $this;
    }

    public function getEmployee()
    {
        return $this->employee;
    }

    public function setEmployee($employee)
    {
        $this->employee = $employee;

        return $this;
    }

    public function getCompany()
    {
        return $this->company;
    }

    public function setCompany($company)
    {
        $this->company = $company;

        return $this;
    }

    public function getWeek(): ?int
    {
        return $this->week;
    }

    public function setWeek(int $week): self
    {
        $this->week = $week;

        return $this;
    }

    public function getYear()
    {
        return $this->year;
    }

    public function setYear($year): self
    {
        $this->year = $year;

        return $this;
    }

    public function hydrate($data, $company, $day, $employee, $converter)
    {
        print_r($data);
        $startTime = new \DateTime($data['startTime']);
        $stopTime = new \DateTime($data['stopTime']);
        $date = new \DateTime($data['date']);

        $this->setCompany($company);
        $this->setDay($day);
        $this->setDayDate($date);
        $this->setEmployee($employee);
        $this->setStartTime($startTime);
        $this->setStopTime($stopTime);
        $this->setWeek($data['week']);
        $this->setYear($data['year']);
        $this->setConvertedStartTime($converter);
        $this->setConvertedStopTime($converter);
    }

    public function getConvertedStartTime()
    {
        return $this->convertedStartTime;
    }

    public function setConvertedStartTime(ConverterController $converter)
    {
        $convertedStartTime = $converter->convertTime($this->startTime);
        $this->convertedStartTime = $convertedStartTime;

        return $this;
    }

    public function getConvertedStopTime()
    {
        return $this->convertedStopTime;
    }

    public function setConvertedStopTime(ConverterController $converter): self
    {
        $convertedStopTime = $converter->convertTime($this->stopTime);
        $this->convertedStopTime = $convertedStopTime;

        return $this;
    }
}
