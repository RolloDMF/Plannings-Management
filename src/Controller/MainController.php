<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\CompanyRepository;

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
        public function home(Request $request, CompanyRepository $companyRepo)
        {
            $companyId = $request->request->all();
            $company = $companyRepo->findOneById($companyId);

            if ($companyId !== []) {
                return $this->render('main/home.html.twig', [
                    'controller_name' => 'MainController',
                    'company' => $company,
                    'page_title' => "Bienvenus ". $this->getUser()->getUsername(),
            ]);           
            }

            return $this->render('main/home.html.twig', [
                'controller_name' => 'MainController',
                'company' => $this->getUser()->getCompanies()[0],
                'page_title' => "Bienvenus ". $this->getUser()->getUsername(),
        ]);
    }
}
