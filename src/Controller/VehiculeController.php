<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Vehicule;
use App\Form\VehiculeType;
use App\Repository\VehiculeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/vehicule')]
class VehiculeController extends EvalAbstractController
{
    #[Route('/', name: 'app_vehicule_index', methods: ['GET'])]
    public function index(VehiculeRepository $vehiculeRepository): Response
    {
        return $this->render('vehicule/index.html.twig', [
            'vehicules' => $vehiculeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_vehicule_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $vehicule = new Vehicule();
        $form = $this->createForm(VehiculeType::class, $vehicule);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($vehicule);
            $entityManager->flush();

            return $this->redirectToRoute('app_vehicule_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('vehicule/new.html.twig', [
            'vehicule' => $vehicule,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/show', name: 'app_vehicule_show', methods: ['GET'])]
    public function show(Vehicule $vehicule): Response
    {
        return $this->render('vehicule/show.html.twig', [
            'vehicule' => $vehicule,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_vehicule_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Vehicule $vehicule, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(VehiculeType::class, $vehicule);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_vehicule_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('vehicule/edit.html.twig', [
            'vehicule' => $vehicule,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/show', name: 'app_vehicule_delete', methods: ['POST'])]
    public function delete(Request $request, Vehicule $vehicule, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$vehicule->getId(), $request->request->get('_token'))) {
            $entityManager->remove($vehicule);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_vehicule_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/search', name: 'app_vehicule_search')]
    public function search(): Response
    {
        $vehicule = $this->vehiculeRepository->findAllVehiculeFree($this->session->get('date_deb'), $this->session->get('date_fin'));
        return $this->render('vehicule/search.html.twig', [
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
