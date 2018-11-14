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
use App\Repository\PlanningRepository;

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
    public function home(Request $request, CompanyRepository $companyRepo, ConverterController $converter, ScheduleRepository $schedulRepo, PlanningRepository $planningRepo)
    {
        if ($this->getUser() == null) {
            return $this->render('main/index.html.twig');
        };

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

        $plannings = $planningRepo->findActualByCompany($company);

            return $this->render('main/home.html.twig', [
                'company' => $company,
                'minSchedule' => $minOpenSchedule,
                'maxSchedule' => $maxCloseSchedule,
                'daysDates' => $daysdates,
                'plannings' => $plannings,
                'page_title' => "Bienvenus ". $this->getUser()->getUsername(),
        ]);
    }        
        
    /**
     * @Route("/company/planning", name="company_planning", methods="GET|POST")
     */
    public function companyPlanning(Request $request, CompanyRepository $companyRepo, ConverterController $converter, ScheduleRepository $schedulRepo, PlanningRepository $planningRepo)
    {
        if ($this->getUser() == null) {
            return $this->render('main/index.html.twig');
        };
        
        $param = $request->request->all();

        $companyId = $param['company'];
        $year = $param['year'];
        $week = $param['week'];  

        $company = $companyRepo->findOneById($companyId);
        $schedules = $schedulRepo->findByCompany($company);

        $minOpenSchedule = $schedulRepo->findMinSchedule($company);
        $maxCloseSchedule = $schedulRepo->findMaxSchedule($company);

        $daysdates = [];
        
        foreach ($schedules as $schedule) {
            $daysdates[] = DateWithYWD::dateWithYearWeek($schedule->getDay()->getrepresentationNumber(), $year, $week);
        }

        $plannings = $planningRepo->findByCompanyYearWeek($company, $year, $week);
        
            return $this->render('main/new.html.twig', [
                'company' => $company,
                'minSchedule' => $minOpenSchedule,
                'maxSchedule' => $maxCloseSchedule,
                'daysDates' => $daysdates,
                'plannings' => $plannings,
                'year' => $year,
                'week' => $week,
                'page_title' => "Bienvenus ". $this->getUser()->getUsername(),
        ]);
    }  
}
