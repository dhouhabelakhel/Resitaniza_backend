<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ManagerResidenceController extends AbstractController
{
    #[Route('/manager/residence', name: 'app_manager_residence')]
    public function index(): Response
    {
        return $this->render('manager_residence/index.html.twig', [
            'controller_name' => 'ManagerResidenceController',
        ]);
    }
}
