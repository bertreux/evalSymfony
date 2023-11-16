<?php

namespace App\Controller;

use App\Repository\CommandeRepository;
use App\Repository\MembreRepository;
use App\Repository\VehiculeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;

class EvalAbstractController extends AbstractController
{
    protected $membreRepository;
    protected $vehiculeRepository;
    protected $commandeRepository;
    protected $session;
    protected $security;
    protected $roleValueAndLibel = [
        'Admin' => 0,
        'Membre' => 1
    ];

    public function __construct(MembreRepository   $membreRepository,
                                VehiculeRepository $vehiculeRepository,
                                CommandeRepository $commandeRepository,
                                RequestStack       $requestStack,
                                Security           $security)
    {
        $this->membreRepository = $membreRepository;
        $this->vehiculeRepository = $vehiculeRepository;
        $this->commandeRepository = $commandeRepository;
        $this->session = $requestStack->getSession();
        $this->security = $security;
    }
}