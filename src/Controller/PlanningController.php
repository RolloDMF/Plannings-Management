<?php

namespace App\Controller;

use App\Entity\Planning;
use App\Form\PlanningType;
use App\Repository\PlanningRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use App\Repository\EmployeeRepository;
use App\Repository\CompanyRepository;
use App\Repository\DayRepository;
use App\Service\ConverterController;
use App\Entity\Company;


/**
 * @Route("/planning")
 */
class PlanningController extends Controller
{
    /**
     * @Route("/", name="planning_index", methods="GET|POST")
     */
    public function index(Request $request, PlanningRepository $planningRepository, CompanyRepository $companyRepo)
    {
        $companyId = $request->request->all();
        
        if ($companyId === []){
            if ($this->getUser()->getCompanies()[0] === null) {
                return $this->redirectToRoute('company_new');
            }
            $companyId = $this->getUser()->getCompanies()[0]->getId();
        }
        $company = $companyRepo->findOneById($companyId);

        return $this->render('planning/index.html.twig', [
            'plannings' => $planningRepository->findAllOrdeByDaydate($company),
            'company' => $company,
            'page_title' => 'Liste des plannings'
            ]);
    }

    /**
     * @Route("/new", name="planning_new", methods="POST")
     */
    public function new(Request $request, SerializerInterface $serializer, EmployeeRepository $employeeRepo, CompanyRepository $companyRepo, DayRepository $dayRepo, ConverterController $converter)
    {
        $datas = $request->request->all();
        
        $planning = new Planning();

        $company = $companyRepo->findOneById($datas['planning']['company']);
        $day = $dayRepo->findOneByRepresentationNumber($datas['planning']['day']);
        $employee = $employeeRepo->findOneById($datas['planning']['employee']);

        $planning->hydrate($datas['planning'], $company, $day, $employee, $converter);


        $em = $this->getDoctrine()->getManager();
        $em->persist($planning);
        $em->flush();

        $json = $serializer->serialize($datas, 'json');
        return new Response($json);
        

    }

    /**
     * @Route("/{id}", name="planning_show", methods="GET")
     */
    public function show(Planning $planning, SerializerInterface $serializer): Response
    {
        $data = [
            "employee" => $planning->getEmployee()->getId(),
            "starttime" => $planning->getStartTime()->format("H:i"),
            "stoptime" => $planning->getStopTime()->format("H:i"),
            "date" => $planning->getDayDate()->format("d/m/Y"),
            "day" => $planning->getDay()->getRepresentationNumber(),
            "company" => $planning->getCompany()->getName(),
        ];

        $json = $serializer->serialize($data, 'json');

        return new Response($json);
    }

    /**
     * @Route("/{id}/edit", name="planning_edit", methods="POST")
     */
    public function edit(Request $request, Planning $planning, SerializerInterface $serializer, EmployeeRepository $employeeRepo, ConverterController $converter)
    {
        $datas = $request->request->all();

        $startTime = new \DateTime($datas['planning']['startTime']);
        $stopTime = new \DateTime($datas['planning']['stopTime']);
        $employee = $employeeRepo->findOneById($datas['planning']['employee']);

        $planning->setEmployee($employee);
        $planning->setStartTime($startTime);
        $planning->setStopTime($stopTime);
        $planning->setConvertedStartTime($converter);
        $planning->setConvertedStopTime($converter);
        $planning->setWorkTime();

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        $json = $serializer->serialize($datas, 'json');
        return new Response($json);

    }

    /**
     * @Route("/{id}", name="planning_delete", methods="DELETE")
     */
    public function delete(Request $request, Planning $planning, SerializerInterface $serializer)
    {

        $em = $this->getDoctrine()->getManager();
        $em->remove($planning);
        $em->flush();

        $response = [
            "success" => true,
        ];

        $json = $serializer->serialize($response, 'json');
        return new Response($json); 
    }

    /**
     * @Route("/lastid/{id}", name="planning_lastid",  methods="GET|POST")
     */
    public function lastId(PlanningRepository $planingRepo, Company $company, SerializerInterface $serializer)
    {
        $lastPlaning = $planingRepo->findLast($company);
        $id = $lastPlaning->getId();

        $json = $serializer->serialize($id, 'json');
        return new Response($json);
    }
}
