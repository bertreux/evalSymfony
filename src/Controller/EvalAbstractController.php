<?php

namespace App\Controller;

use App\Repository\MembreRepository;
use App\Repository\VehiculeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;

class EvalAbstractController extends AbstractController
{
    protected $membreRepository;
    protected $vehiculeRepository;
    protected $session;
    protected $security;

    public function __construct(MembreRepository   $membreRepository,
                                VehiculeRepository $vehiculeRepository,
                                RequestStack       $requestStack,
                                Security           $security)
    {
        $this->membreRepository = $membreRepository;
        $this->vehiculeRepository = $vehiculeRepository;
        $this->session = $requestStack->getSession();
        $this->security = $security;
    }
}