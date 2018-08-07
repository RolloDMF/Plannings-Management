<?php

namespace App\Controller;

use App\Entity\Manager;
use App\Form\ManagerType;
use App\Repository\ManagerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/manager")
 */
class ManagerController extends Controller
{
    /**
     * @Route("/signin", name="manager_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $manager = new Manager();
        $form = $this->createForm(ManagerType::class, $manager);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($manager);

            $manager->setEnabled(true);
            $manager->setSuperAdmin(true);

            $em->flush();

            return $this->redirectToRoute('login', [
            "manager" => $manager,
            ]);
        }

        return $this->render('manager/new.html.twig', [
            'manager' => $manager,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="manager_show", methods="GET")
     */
    public function show(Manager $manager): Response
    {
        return $this->render('manager/show.html.twig', [
            'manager' => $manager,
            'page_title' => 'Profile'
            ]);
    }

    /**
     * @Route("/{id}/edit", name="manager_edit", methods="GET|POST")
     */
    public function edit(Request $request, Manager $manager): Response
    {
        $form = $this->createForm(ManagerType::class, $manager);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('manager_edit', ['id' => $manager->getId()]);
        }

        return $this->render('manager/edit.html.twig', [
            'manager' => $manager,
            'form' => $form->createView(),
            'page_title' => 'Edition de Profile'
        ]);
    }

    /**
     * @Route("/{id}", name="manager_delete", methods="DELETE")
     */
    public function delete(Request $request, Manager $manager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$manager->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($manager);
            $em->flush();
        }

        return $this->redirectToRoute('manager_index');
    }
}
