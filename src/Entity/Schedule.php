<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Service\ConverterController;

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
     * @ORM\Column(type="time", nullable=true)
     */
    private $firstTimeStart;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $firstTimeStop;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $secondTimeStart;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $secondTimeStop;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Company", inversedBy="schedules")
     * @ORM\JoinColumn(nullable=false)
     */
    private $company;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $convertedFirstTimeStart;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $convertedFirstTimeStop;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $convertedSecondTimeStart;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $convertedSecondTimeStop;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $startTime;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $morningWorkTime;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $afternoonWorkTime;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $mealBreak;

    public function getId()
    {
        return $this->id;
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

    public function getFirstTimeStart()
    {
        return $this->firstTimeStart;
    }

    public function setFirstTimeStart($firstTimeStart): self
    {
        $this->firstTimeStart = $firstTimeStart;

        return $this;
    }

    public function getFirstTimeStop()
    {
        return $this->firstTimeStop;
    }

    public function setFirstTimeStop($firstTimeStop): self
    {
        $this->firstTimeStop = $firstTimeStop;

        return $this;
    }

    public function getSecondTimeStart()
    {
        return $this->secondTimeStart;
    }

    public function setSecondTimeStart($secondTimeStart): self
    {
        $this->secondTimeStart = $secondTimeStart;

        return $this;
    }

    public function getSecondTimeStop()
    {
        return $this->secondTimeStop;
    }

    public function setSecondTimeStop($secondTimeStop): self
    {
        $this->secondTimeStop = $secondTimeStop;

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

    public function hydrate($datas)
    {
        if ($datas['firstTimeStart'] === "") {
            $time1 = null;
        }else{     
            $time1 = \DateTime::createFromFormat('G:i', ($datas['firstTimeStart']));
        }

        if ($datas['firstTimeStop'] === "") {
            $time2 = null;
        }else{
            $time2 = \DateTime::createFromFormat('G:i', ($datas['firstTimeStop']));
        }

        if ($datas['secondTimeStart'] === "") {
            $time3 = null;
        }else{
            $time3 = \DateTime::createFromFormat('G:i', ($datas['secondTimeStart']));
        }

        if ($datas['secondTimeStop'] === "") {
            $time4 = null;
        }else{
            $time4 = \DateTime::createFromFormat('G:i', ($datas['secondTimeStop']));
        }

        $this->setFirstTimeStart($time1);
        $this->setFirstTimeStop($time2);
        $this->setSecondTimeStart($time3);
        $this->setSecondTimeStop($time4);

    }

    public function getConvertedFirstTimeStart(): ?int
    {
        return $this->convertedFirstTimeStart;
    }

    public function setConvertedFirstTimeStart(?int $convertedFirstTimeStart): self
    {
        $this->convertedFirstTimeStart = $convertedFirstTimeStart;

        return $this;
    }

    public function getConvertedFirstTimeStop(): ?int
    {
        return $this->convertedFirstTimeStop;
    }

    public function setConvertedFirstTimeStop(?int $convertedFirstTimeStop): self
    {
        $this->convertedFirstTimeStop = $convertedFirstTimeStop;

        return $this;
    }

    public function getConvertedSecondTimeStart(): ?int
    {
        return $this->convertedSecondTimeStart;
    }

    public function setConvertedSecondTimeStart(?int $convertedSecondTimeStart): self
    {
        $this->convertedSecondTimeStart = $convertedSecondTimeStart;

        return $this;
    }

    public function getConvertedSecondTimeStop(): ?int
    {
        return $this->convertedSecondTimeStop;
    }

    public function setConvertedSecondTimeStop(?int $convertedSecondTimeStop): self
    {
        $this->convertedSecondTimeStop = $convertedSecondTimeStop;

        return $this;
    }

    public function getStartTime(): ?int
    {
        return $this->startTime;
    }

    public function setStartTime(?int $startTime): self
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getMorningWorkTime(): ?int
    {
        return $this->morningWorkTime;
    }

    public function setMorningWorkTime(?int $morningWorkTime): self
    {
        $this->morningWorkTime = $morningWorkTime;

        return $this;
    }

    public function getAfternoonWorkTime(): ?int
    {
        return $this->afternoonWorkTime;
    }

    public function setAfternoonWorkTime(?int $afternoonWorkTime): self
    {
        $this->afternoonWorkTime = $afternoonWorkTime;

        return $this;
    }

    public function getMealBreak(): ?int
    {
        return $this->mealBreak;
    }

    public function setMealBreak(?int $mealBreak): self
    {
        $this->mealBreak = $mealBreak;

        return $this;
    }

}
