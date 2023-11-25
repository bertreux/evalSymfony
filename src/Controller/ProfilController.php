<?php

namespace App\Controller;

use App\Form\ProfilType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ProfilController extends EvalAbstractController
{
    #[Route('/profil', name: 'app_profil')]
    public function index(): Response
    {
        if($this->getUser() == null){
            return $this->redirectToRoute('homepage');
        }

        return $this->render('profil/index.html.twig', []);
    }

    #[Route('/profil/information', name: 'app_profil_information')]
    public function information(Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        if($this->getUser() == null){
            return $this->redirectToRoute('homepage');
        }

        $membre = $this->membreRepository->find($this->getUser()->getId());
        $form = $this->createForm(ProfilType::class, $membre, []);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            if($form->isValid()){
                $membre->setMdp(
                    $userPasswordHasher->hashPassword(
                        $membre,
                        $membre->getMdp()
                    )
                );

                $this->security->login($membre);

                $this->membreRepository->add($membre, true);
                return $this->redirectToRoute('app_profil');
            }
        }
        return $this->render('profil/information.html.twig', [
            'form' => $form
        ]);
    }
}
