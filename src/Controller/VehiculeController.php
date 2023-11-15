<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Vehicule;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VehiculeController extends EvalAbstractController
{
    #[Route('/vehicule', name: 'app_vehicule')]
    public function index(): Response
    {
        //$vehicule = $this->vehiculeRepository->findAll();
        $vehicule = $this->vehiculeRepository->findAllVehiculeFree($this->session->get('date_deb'), $this->session->get('date_fin'));
        return $this->render('vehicule/index.html.twig', [
            'vehicules' => $vehicule
        ]);
    }

    #[Route('/{id}/reservation', name: 'app_reservation')]
    public function reservation(Vehicule $vehicule)
    {
        return $this->render('reservation/index.html.twig', [
            'vehicules' => $vehicule,
            'dateDeb' => $this->session->has('date_deb') ? $this->session->get('date_deb') : new \DateTime('now'),
            'dateFin' => $this->session->has('date_fin') ? $this->session->get('date_fin') : new \DateTime('now'),
        ]);
    }

    #[Route('/{id}/reservation/create', name: 'app_reservation_create')]
    public function createReservation(Vehicule $vehicule)
    {
        $commande = new Commande();
        $commande->setDateEnregistrement(new \DateTime('now'))
            ->setMembre($this->getUser())
            ->setVehicule($vehicule)
            ->setDateHeurDepart($this->session->get('date_deb'))
            ->setDateHeurFin($this->session->get('date_fin'));
        $this->commandeRepository->add($commande, true);
        return $this->redirectToRoute('homepage');
    }
}
