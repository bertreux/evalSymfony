<?php

namespace App\Controller;

use App\Form\AccueilType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends EvalAbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(Request $request): Response
    {
        $form = $this->createForm(AccueilType::class, null, []);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            if($form->isValid()){
                $data = $form->getData();
                $this->session->set('date_deb', $data['date_heur_depart']);
                $this->session->set('date_fin', $data['date_heur_fin']);
                return $this->redirectToRoute('app_vehicule');
            }
        }
        return $this->render('home/index.html.twig', [
            'form' => $form
        ]);
    }
}
