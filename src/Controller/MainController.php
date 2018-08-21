<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\CompanyRepository;
use App\Service\ConverterController;
use App\Entity\Schedule;
use App\Repository\ScheduleRepository;
use App\Form\PlanningType;
use App\Service\DateWithYWD;

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
            if ($this->getUser()->getCompanies()[0] === null) {
                return $this->redirectToRoute('company_new');
            }
            $companyId = $this->getUser()->getCompanies()[0]->getId();
        }
        $company = $companyRepo->findOneById($companyId);
        $schedules = $schedulRepo->findByCompany($company);

        $minOpenSchedule = $schedulRepo->findMinSchedule($company);
        $maxCloseSchedule = $schedulRepo->findMaxSchedule($company);

        $daysdates = [];
        
        foreach ($schedules as $schedule) {
            $daysdates[] = DateWithYWD::dateWithDayActualWeek($schedule->getDay()->getrepresentationNumber());
        }

            return $this->render('main/home.html.twig', [
                'company' => $company,
                'minSchedule' => $minOpenSchedule,
                'maxSchedule' => $maxCloseSchedule,
                'daysDates' => $daysdates,
                'page_title' => "Bienvenus ". $this->getUser()->getUsername(),
        ]);
    }        

    
}
