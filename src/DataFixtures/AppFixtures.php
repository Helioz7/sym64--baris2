<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Section;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;
    private SluggerInterface $slugger;

    public function __construct(UserPasswordHasherInterface $passwordHasher, SluggerInterface $slugger)
    {
        $this->passwordHasher = $passwordHasher;
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        // Créer 30 utilisateurs
        $admin = new User();
        $admin->setUsername('admin')
            ->setPassword($this->passwordHasher->hashPassword($admin, 'admin'))
            ->setRoles(['ROLE_ADMIN'])
            ->setFullname($faker->name())
            ->setEmail($faker->unique()->email())
            ->setUniqid(uniqid())
            ->setActivate(true);
        $manager->persist($admin);

        // Créer 5 rédacteurs
        for ($i = 1; $i <= 5; $i++) {
            $redac = new User();
            $redac->setUsername("redac$i")
                ->setPassword($this->passwordHasher->hashPassword($redac, "redac$i"))
                ->setRoles(['ROLE_REDAC'])
                ->setFullname($faker->name())
                ->setEmail($faker->unique()->email())
                ->setUniqid(uniqid())
                ->setActivate(true);
            $manager->persist($redac);
        }

        // Créer 24 utilisateurs
        for ($i = 1; $i <= 24; $i++) {
            $user = new User();
            $user->setUsername("user$i")
                ->setPassword($this->passwordHasher->hashPassword($user, "user$i"))
                ->setRoles(['ROLE_USER'])
                ->setFullname($faker->name())
                ->setEmail($faker->unique()->email())
                ->setUniqid(uniqid())
                ->setActivate($i % 4 !== 0); // 3 sur 4 actifs
            $manager->persist($user);
        }

        // Créer 6 sections
        $sections = [];
        for ($j = 1; $j <= 6; $j++) {
            $section = new Section();
            $section->setSectionTitle($faker->sentence(2))
                ->setSectionSlug($this->slugger->slug($section->getSectionTitle())->lower())
                ->setSectionDetail($faker->text());

            $manager->persist($section);
            $sections[] = $section; // Stocker les sections pour les associer aux articles
        }

        // Créer 160 articles
        for ($k = 1; $k <= 160; $k++) {
            $article = new Article();
            $title = $faker->sentence(6);
            $article->setTitle($title)
                ->setTitleSlug($this->slugger->slug($title)->lower())
                ->setText($faker->text())
                ->setArticleDateCreate($faker->dateTimeBetween('-6 months', 'now'))
                ->setPublished($faker->boolean(75)); // 75% de chance d'être publié

            // Si publié, définir une date de publication
            if ($article->isPublished()) {
                $article->setArticleDatePosted($faker->dateTimeBetween($article->getArticleDateCreate(), 'now'));
            }

            // Assigner un auteur aléatoire (admin ou redacteur)
            $author = $manager->getRepository(User::class)->findOneBy(['roles' => ['ROLE_REDAC']]) 
                ?? $admin; // Prioriser un rédacteur, sinon l'admin
            $article->setUser($author);

            // Assigner aléatoirement entre 2 et 40 articles à une section
            $randomSectionCount = rand(2, 40); // Nombre d'articles à ajouter à une section
            for ($m = 0; $m < $randomSectionCount; $m++) {
                $randomSection = $sections[array_rand($sections)];
                $randomSection->addArticle($article);
            }

            $manager->persist($article);
        }

        $manager->flush();
    }
}
