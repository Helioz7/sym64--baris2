<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ConnexiontController extends AbstractController
{
    #[Route('/connexiont', name: 'app_connexiont')]
    public function index(): Response
    {
        return $this->render('connexiont/index.html.twig', [
            'controller_name' => 'ConnexiontController',
        ]);
    }
}
