<?php

namespace App\Service;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\Schedule;
use App\Repository\ScheduleRepository;

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
            $minutesFloat = ($time->format("i")) / 60;
            dump($minutesFloat);

        }else {
            $minutesFloat = 0;
            dump($minutesFloat);

        }
        $formatedTime = ($time->format("G")) + $minutesFloat;
        var_dump($formatedTime);

        return ($formatedTime);
    }
}
