<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Section;
use App\Entity\Article;
use App\Repository\SectionRepository;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;

class HomepageController extends AbstractController
{
    #[Route('/', name: 'app_homepage')]
    public function index(
        EntityManagerInterface $em,
        SectionRepository $sections,
        ArticleRepository $articles
    ): Response {
        return $this->render('homepage/index.html.twig', [
            'menu' => $sections->findAll(),
            'sections' => $sections->findAll(),
            'articles' => $articles->findBy([], null, 10),
        ]);
    }

    #[Route('/section/{slug}', name: 'app_section')]
    public function section(
        SectionRepository $sections,
        ArticleRepository $articles,
        string $slug
    ): Response {
        // Récupération de la section spécifique
        $section = $sections->findOneBy(["sectionSlug" => $slug]);

        
        if (!$section) {
            throw $this->createNotFoundException('La section demandée n\'existe pas');
        }

        return $this->render('homepage/section.html.twig', [
            'title' => 'Section ' . $section->getSectionTitle(),
            'sectionDetail' => $section->getSectionDetail(),
            'section' => $section,
            'sections' => $sections->findAll(), 
            'articles' => $articles->findBy([], null, 10),
        ]);
    }
}