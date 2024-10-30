<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Section;
use App\Entity\Article;
use App\Repository\SectionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;

class HomepageController extends AbstractController
{
    #[Route('/', name: 'app_homepage')]
    public function index(EntityManagerInterface $em,SectionRepository $sections): Response
    {
        $menu=$em->getRepository(Section::class)->findAll();
        $articles = $em->getRepository(Article::class)->findAll();
        return $this->render('homepage/index.html.twig', [
            'menu' => $menu,
            'sections' => $sections->findAll(),
            'articles' => $articles,
        ]);
    }


    #[Route('section/{slug}', name: 'app_section')]
    public function section(EntityManagerInterface $em,SectionRepository $sections,string $slug): Response
    {
        $menu=$em->getRepository(Section::class)->findAll();
        return $this->render('homepage/index.html.twig', [
            'menu' => $menu,
            'sections' => $sections->findAll(),
        ]);
    }
}
