<?php

namespace App\Controller;

use App\Entity\Membre;
use App\Entity\Vehicule;
use App\Form\InscriptionType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class VehiculeController extends EvalAbstractController
{
    #[Route('/vehicule', name: 'app_vehicule')]
    public function index(): Response
    {
        $vehicule = $this->vehiculeRepository->findAll();
        return $this->render('vehicule/index.html.twig', [
            'vehicules' => $vehicule
        ]);
    }

    #[Route('/{id}/reservation', name: 'app_reservation')]
    public function reservation(Vehicule $vehicule, Request $request,
                                UserPasswordHasherInterface $userPasswordHasher){
        $membre = new Membre();
        $form = $this->createForm(InscriptionType::class,$membre,[]);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $membre = $form->getData();
            if($this->membreRepository->findOneBy(['email' => $membre->getEmail()])){
                $form->addError(new FormError("L'email utilisé existe déja"));
            }
            if($form->isValid()){
                $membre->setStatut(0)
                    ->setDateEnregistrement(new \DateTime('now'));

                $membre->setMdp(
                    $userPasswordHasher->hashPassword(
                        $membre,
                        $membre->getMdp()
                    )
                );

                $this->security->login($membre);

                $this->security->logout(false);

                $this->membreRepository->add($membre, true);
            }
        }
        return $this->render('reservation/index.html.twig', [
            'form' => $form,
            'vehicules' => $vehicule,
            'dateDeb' => $this->session->has('date_deb') ? $this->session->get('date_deb') : new \DateTime('now'),
            'dateFin' => $this->session->has('date_fin') ? $this->session->get('date_fin') : new \DateTime('now'),
        ]);
    }
}
