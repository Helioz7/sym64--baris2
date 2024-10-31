<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ArticleDetailController extends AbstractController
{
    #[Route('/article/detail', name: 'app_article_detail')]
    public function index(): Response
    {
        return $this->render('article_detail/index.html.twig', [
            'controller_name' => 'ArticleDetailController',
        ]);
    }
}
