<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\CompanyRepository;
use App\Service\ConverterController;
use App\Entity\Schedule;
use App\Repository\ScheduleRepository;

class MainController extends Controller
{
    /**
     * @Route("/", name="landing")
     */
    public function landing()
    {
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
            ]);
    }
        
    /**
     * @Route("/home", name="home", methods="GET|POST")
     */
    public function home(Request $request, CompanyRepository $companyRepo, ConverterController $converter, ScheduleRepository $schedulRepo)
    {
        $companyId = $request->request->all();
        
        if ($companyId === []){
            $companyId = $this->getUser()->getCompany()->getId();
        }
        $company = $companyRepo->findOneById($companyId);
        $schedules = $schedulRepo->findByCompany($company);

        $formatedSchedules = [];
        
        foreach ($schedules as $schedule) {
            $formatedSchedule = $converter->convert($schedule->getId(), $schedulRepo);
            $formatedSchedules[] = $formatedSchedule;
            dump($formatedSchedule);
        }

            return $this->render('main/home.html.twig', [
                'company' => $company,
                'formatedSchedules' => $formatedSchedules,
                'page_title' => "Bienvenus ". $this->getUser()->getUsername(),
        ]);
    }        

    
}
