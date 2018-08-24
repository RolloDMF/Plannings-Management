<?php

namespace App\Controller;

use App\Entity\Schedule;
use App\Form\ScheduleType;
use App\Repository\ScheduleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\DayRepository;
use App\Entity\Company;
use App\Entity\Day;
use App\Repository\CompanyRepository;
use App\Service\ConverterController;

/**
 * @Route("/schedule")
 */
class ScheduleController extends Controller
{
    /**
     * @Route("/", name="schedule_index", methods="GET")
     */
    public function index(ScheduleRepository $scheduleRepository): Response
    {
        return $this->render('schedule/index.html.twig', [
            'schedules' => $scheduleRepository->findAll(),
            'page_title' => 'Horraires de l\'entreprise'
            ]);
    }

    /**
     * @Route("/new", name="schedule_new", methods="GET|POST")
     */
    public function new(Request $request, DayRepository $dayRepo, CompanyRepository $companyRepo, ConverterController $converter): Response
    {


            $companySchedules = $request->request->all();
            $em = $this->getDoctrine()->getManager();
            
            foreach ($companySchedules as $datas) {
                
                $company = $companyRepo->findOneById($datas['company']);
                $day = $dayRepo->findOneByName($datas['day']);
                
                $schedule = new Schedule();
                $schedule->hydrate($datas, $converter);
                $schedule->setCompany($company);
                $schedule->setDay($day);
                
                $em->persist($schedule);
                
            }
            $em->flush();

            return $this->redirectToRoute('company_index');

    }

    /**
     * @Route("/{id}", name="schedule_show", methods="GET")
     */
    public function show(Schedule $schedule): Response
    {
        return $this->render('schedule/show.html.twig', [
            'schedule' => $schedule,
            'page_title' => 'Horraire'
            ]);
    }

    /**
     * @Route("/edit", name="schedule_edit", methods="GET|POST")
     */
    public function edit(Request $request, ScheduleRepository $scheduleRepo, ConverterController $converter)
    {
        $companySchedules = $request->request->all();
        $em = $this->getDoctrine()->getManager();
        
        foreach ($companySchedules as $datas) {
            
            $schedule = $scheduleRepo->findOneById($datas['id']);
            
            $schedule->hydrate($datas, $converter);
            
            $em->flush();

        }

        return $this->redirectToRoute('company_index');
    }

    /**
     * @Route("{id}/edit/form", name="schedule_edit_form", methods="GET|POST")
     */
    public function showEditForm(Company $company, ScheduleRepository $scheduleRepo)
    {
        $schedules = $scheduleRepo->findByCompany($company);

        return $this->render('schedule/edit.html.twig', [
            'schedules' => $schedules,
            'page_title' => 'Edition Horraire de l entreprise '. $company->getName()
        ]);
    }

    /**
     * @Route("/{id}", name="schedule_delete", methods="DELETE")
     */
    public function delete(Request $request, Schedule $schedule): Response
    {
        if ($this->isCsrfTokenValid('delete'.$schedule->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($schedule);
            $em->flush();
        }

        return $this->redirectToRoute('schedule_index');
    }
}
