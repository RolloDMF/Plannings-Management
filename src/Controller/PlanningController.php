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


/**
 * @Route("/planning")
 */
class PlanningController extends Controller
{
    /**
     * @Route("/", name="planning_index", methods="GET")
     */
    public function index(PlanningRepository $planningRepository): Response
    {
        return $this->render('planning/index.html.twig', [
            'plannings' => $planningRepository->findAll(),
            'page_title' => 'Liste des plannings'
            ]);
    }

    /**
     * @Route("/new", name="planning_new", methods="GET|POST")
     */
    public function new(Request $request, SerializerInterface $serializer)
    {
        $datas = $request->request->all();
        $json = $serializer->serialize($datas, 'json');

        return new Response($json);
/*         $planning = new Planning();
        $form = $this->createForm(PlanningType::class, $planning);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($planning);
            $em->flush();

            return $this->redirectToRoute('planning_index');
        }

        return $this->render('planning/new.html.twig', [
            'planning' => $planning,
            'form' => $form->createView(),
            'page_title' => 'Nouveau planning'
        ]); */
    }

    /**
     * @Route("/{id}", name="planning_show", methods="GET")
     */
    public function show(Planning $planning): Response
    {
        return $this->render('planning/show.html.twig', [
            'planning' => $planning,
            'page_title' => 'Planning semaine n° '
            ]);
    }

    /**
     * @Route("/{id}/edit", name="planning_edit", methods="GET|POST")
     */
    public function edit(Request $request, Planning $planning): Response
    {
        $form = $this->createForm(PlanningType::class, $planning);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute(
                'planning_edit', ['id' => $planning->getId()]);
        }

        return $this->render('planning/edit.html.twig', [
            'planning' => $planning,
            'form' => $form->createView(),
            'page_title' => 'Planning semaine n° '
        ]);
    }

    /**
     * @Route("/{id}", name="planning_delete", methods="DELETE")
     */
    public function delete(Request $request, Planning $planning): Response
    {
        if ($this->isCsrfTokenValid('delete'.$planning->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($planning);
            $em->flush();
        }

        return $this->redirectToRoute('planning_index');
    }
}
