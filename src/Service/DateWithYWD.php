<?php

namespace App\Service;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DateWithYWD extends Controller
{
    public static function dateWithDayActualWeek($dayNumber)
    {
        $dayNumber -= 1;
        $dateDay = new \DateTime();
        $currentWeek = new \DateTime();
        $currentYear = new \DateTime();

        $currentYearFormated = $currentYear->format('o');
        $currentWeekFormated = $currentWeek->format('W');

        $date = $dateDay->setISOdate($currentYearFormated, $currentWeekFormated);
        $dateDay->modify('+' . $dayNumber . 'day' );
        
        return $date;
    }
}