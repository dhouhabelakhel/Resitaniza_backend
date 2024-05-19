<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class OcuppationController extends AbstractController
{
    #[Route('/ocuppation', name: 'app_ocuppation')]
    public function index(): Response
    {
        return $this->render('ocuppation/index.html.twig', [
            'controller_name' => 'OcuppationController',
        ]);
    }
}
