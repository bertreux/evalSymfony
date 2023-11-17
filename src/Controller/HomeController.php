<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Form\AccueilType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends EvalAbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(Request $request): Response
    {
        $commande = new Commande();
        $form = $this->createForm(AccueilType::class, $commande, []);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            if($form->isValid()){
                $this->session->set('date_deb', $commande->getDateHeurDepart());
                $this->session->set('date_fin', $commande->getDateHeurFin());
                return $this->redirectToRoute('app_vehicule_search');
            }else{
                $this->showErrorFlashWithArray($form->all());
            }
        }
        return $this->render('home/index.html.twig', [
            'form' => $form
        ]);
    }
}
