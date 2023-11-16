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
            if($commande->getDateHeurFin() != null && $commande->getDateHeurDepart() != null &&
                $commande->getDateHeurFin() < $commande->getDateHeurDepart()){
                $form->get('date_heur_fin')->addError(new FormError('La date de fin doit être supérieur a la date de début'));
            }
            if($form->isValid()){
                $this->session->set('date_deb', $commande->getDateHeurDepart());
                $this->session->set('date_fin', $commande->getDateHeurFin());
                return $this->redirectToRoute('app_vehicule_search');
            }else{
                $this->showErrorFlash($commande);
            }
        }
        return $this->render('home/index.html.twig', [
            'form' => $form
        ]);
    }
}
