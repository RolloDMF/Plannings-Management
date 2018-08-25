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
     * @ORM\Column(type="decimal", precision=4, scale=2, nullable=true)
     */
    private $convertedFirstTimeStart;

    /**
     * @ORM\Column(type="decimal", precision=4, scale=2, nullable=true)
     */
    private $convertedFirstTimeStop;

    /**
     * @ORM\Column(type="decimal", precision=4, scale=2, nullable=true)
     */
    private $convertedSecondTimeStart;

    /**
     * @ORM\Column(type="decimal", precision=4, scale=2, nullable=true)
     */
    private $convertedSecondTimeStop;

    /**
     * @ORM\Column(type="decimal", precision=4, scale=2, nullable=true)
     */
    private $morningWorkTime;

    /**
     * @ORM\Column(type="decimal", precision=4, scale=2, nullable=true)
     */
    private $afternoonWorkTime;

    /**
     * @ORM\Column(type="decimal", precision=4, scale=2, nullable=true)
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
        if ($firstTimeStart !== null) {
            $this->firstTimeStart = ConverterController::roundTime($firstTimeStart);
        }else{
            $this->firstTimeStart = $firstTimeStart;
        };

        return $this;
    }

    public function getFirstTimeStop()
    {
        return $this->firstTimeStop;
    }

    public function setFirstTimeStop($firstTimeStop): self
    {
        if ($firstTimeStop !== null) {
            $this->firstTimeStop = ConverterController::roundTime($firstTimeStop);
        }else{
            $this->firstTimeStop = $firstTimeStop;
        };

        return $this;
    }

    public function getSecondTimeStart()
    {
        return $this->secondTimeStart;
    }

    public function setSecondTimeStart($secondTimeStart): self
    {
        if ($secondTimeStart !== null) {
            $this->secondTimeStart = ConverterController::roundTime($secondTimeStart);
        }else{
            $this->secondTimeStart = $secondTimeStart;
        };

        return $this;
    }

    public function getSecondTimeStop()
    {
        return $this->secondTimeStop;
    }

    public function setSecondTimeStop($secondTimeStop): self
    {
        if ($secondTimeStop !== null) {
            $this->secondTimeStop = ConverterController::roundTime($secondTimeStop);
        }else{
            $this->secondTimeStop = $secondTimeStop;
        };

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

    public function hydrate($datas, ConverterController $converter)
    {
        if ($datas['firstTimeStart'] === "") {
            $time1 = null;
            //set this parameter null in case of schedule edit
            $this->setConvertedFirstTimeStart(null);
        }else{     
            $time1 = \DateTime::createFromFormat('G:i', ($datas['firstTimeStart']));
            $this->setConvertedFirstTimeStart($converter->convertTime($time1));
        }

        if ($datas['firstTimeStop'] === "") {
            $time2 = null;
            //set this parameter null in case of schedule edit
            $this->setConvertedFirstTimeStop(null);
            $this->setMorningWorkTime(null);
        }else{
            $time2 = \DateTime::createFromFormat('G:i', ($datas['firstTimeStop']));
            $this->setConvertedFirstTimeStop($converter->convertTime($time2));
            // if there is a first time stop , we define morning work time
            $this->setMorningWorkTime(($this->convertedFirstTimeStop - $this->convertedFirstTimeStart) * 4);
        }

        if ($datas['secondTimeStart'] === "") {
            $time3 = null;
            //set this parameter null in case of schedule edit
            $this->setConvertedSecondTimeStart(null);
            $this->setMealBreak(null);
        }else{
            $time3 = \DateTime::createFromFormat('G:i', ($datas['secondTimeStart']));
            $this->setConvertedSecondTimeStart($converter->convertTime($time3));
            //if there is a second time start , we define mealbreak
            $this->setMealBreak(($this->convertedSecondTimeStart - $this->convertedFirstTimeStop) * 4);
        }

        if ($datas['secondTimeStop'] === "") {
            $time4 = null;
            //set this parameter null in case of schedule edit
            $this->setConvertedSecondTimeStop(null);
            $this->setAfternoonWorkTime(null);
        }else{
            $time4 = \DateTime::createFromFormat('G:i', ($datas['secondTimeStop']));
            $this->setConvertedSecondTimeStop($converter->convertTime($time4));
            //if there is a second time stop, we define afternoon wok time
            $this->setAfternoonWorkTime(($this->convertedSecondTimeStop - $this->convertedSecondTimeStart) * 4);
        }

        $this->setFirstTimeStart($time1);
        $this->setFirstTimeStop($time2);
        $this->setSecondTimeStart($time3);
        $this->setSecondTimeStop($time4);

    }

    public function getConvertedFirstTimeStart()
    {
        return $this->convertedFirstTimeStart;
    }

    public function setConvertedFirstTimeStart($convertedFirstTimeStart): self
    {
        $this->convertedFirstTimeStart = $convertedFirstTimeStart;

        return $this;
    }

    public function getConvertedFirstTimeStop()
    {
        return $this->convertedFirstTimeStop;
    }

    public function setConvertedFirstTimeStop($convertedFirstTimeStop): self
    {
        $this->convertedFirstTimeStop = $convertedFirstTimeStop;

        return $this;
    }

    public function getConvertedSecondTimeStart()
    {
        return $this->convertedSecondTimeStart;
    }

    public function setConvertedSecondTimeStart($convertedSecondTimeStart): self
    {
        $this->convertedSecondTimeStart = $convertedSecondTimeStart;

        return $this;
    }

    public function getConvertedSecondTimeStop()
    {
        return $this->convertedSecondTimeStop;
    }

    public function setConvertedSecondTimeStop($convertedSecondTimeStop): self
    {
        $this->convertedSecondTimeStop = $convertedSecondTimeStop;

        return $this;
    }

    public function getMorningWorkTime()
    {
        return $this->morningWorkTime;
    }

    public function setMorningWorkTime($morningWorkTime): self
    {
        $this->morningWorkTime = $morningWorkTime;

        return $this;
    }

    public function getAfternoonWorkTime()
    {
        return $this->afternoonWorkTime;
    }

    public function setAfternoonWorkTime($afternoonWorkTime): self
    {
        $this->afternoonWorkTime = $afternoonWorkTime;

        return $this;
    }

    public function getMealBreak()
    {
        return $this->mealBreak;
    }

    public function setMealBreak($mealBreak): self
    {
        $this->mealBreak = $mealBreak;

        return $this;
    }

}
