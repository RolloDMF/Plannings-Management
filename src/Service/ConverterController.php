<?php

namespace App\Service;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\Schedule;
use App\Repository\ScheduleRepository;
use PhpParser\Node\Stmt\Break_;

class ConverterController extends Controller
{

    public function convert($id, ScheduleRepository $scheduleRepo)
    {
        $schedule = $scheduleRepo->findOneById($id);

        if ($schedule->getFirstTimeStart() !== null ) {

            $firstTimeStart = $this->convertTime($schedule->getFirstTimeStart());
            $firstTimeStop = $this->convertTime($schedule->getFirstTimeStop());

        }else {
            $firstTimeStart = null;
            $firstTimeStop = null;
        }
        if ($schedule->getSecondTimeStart() !== null ) {

            $secondTimeStart = $this->convertTime($schedule->getSecondTimeStart());
            $secondTimeStop = $this->convertTime($schedule->getSecondTimeStop());

        }else {
            $secondTimeStart = null;
            $secondTimeStop = null;
        }

        $schedules = [
            "id" => $id,
            "firstTimeStart" => $firstTimeStart,
            "firstTimeStop" => $firstTimeStop,
            "secondTimeStart" => $secondTimeStart,
            "secondTimeStop" => $secondTimeStop,
            "day" => $schedule->getDay()
        ];

        return $schedules;
        
    }

    public function convertTime($time)
    {
        //converte base 60 number to base 100
        if (($time->format("i")) !== 0) {
            $minutesFloat = ($time->format("i")) / 0.6;
        }else {
            $minutesFloat = 0;
        }
        if ($minutesFloat >= 88) {
            $formatedTime = ($time->format("G")) + 1;
        }else{
            $formatedTime = ($time->format("G")) + $this->round($minutesFloat);
        }

        return ($formatedTime);
    }

    public function round($minutesFloat)
    {
        //we transform value on quarter of 100
        switch (true) {
            case ($minutesFloat >= 13 && $minutesFloat < 38):
                $minutesFloat = 25/100;
                return $minutesFloat;
                break;

            case ($minutesFloat >= 38 && $minutesFloat < 63):
                $minutesFloat = 50/100;
                return $minutesFloat;
                break;

            case ($minutesFloat >= 63 && $minutesFloat < 88):
                $minutesFloat = 75/100;
                return $minutesFloat;
                break;

            default:
                $minutesFloat = 0;
                return $minutesFloat;
                break;
        }
    }
}
