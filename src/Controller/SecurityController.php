<?php

namespace App\Controller;

use App\Entity\Membre;
use App\Form\InscriptionType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends EvalAbstractController
{
    #[Route('/connexion', name: 'app_login')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        if(!is_null($error)){
            $error = "Email et/ou mot de passe invalide";
            $this->addFlash('danger', $error);
        }

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route('/déconnexion', name: 'logout')]
    public function logout(): Response
    {
        $this->security->logout(false);

        return $this->redirectToRoute('homepage');
    }

    #[Route('/inscription', name: 'app_insription')]
    public function inscription(Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $membre = new Membre();
        $form = $this->createForm(InscriptionType::class, $membre, []);

        $form->handleRequest($request);

        if($form->isSubmitted()){
            if($form->isValid()){
                $membre->setDateEnregistrement(new \DateTime('now'))
                    ->setStatut(0);

                $membre->setMdp(
                    $userPasswordHasher->hashPassword(
                        $membre,
                        $membre->getMdp()
                    )
                );

                $this->security->login($membre);

                $this->membreRepository->add($membre, true);
                return $this->redirectToRoute('homepage');
            }else{
                $this->showErrorFlashWithArray($form->all());
            }
        }

        return $this->render('security/register.html.twig', [
            'form' => $form
        ]);
    }
}