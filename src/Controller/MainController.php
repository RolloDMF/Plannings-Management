<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
     * @Route("/home", name="home")
     */
    public function home()
    {
        return $this->render('main/home.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }
}
