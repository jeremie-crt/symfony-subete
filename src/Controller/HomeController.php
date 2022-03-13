<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comments;
use App\Entity\Tags;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class HomeController extends AbstractController
{

    private UserPasswordHasherInterface $hasher;
    private SluggerInterface $slugger;
    private EntityManagerInterface $manager;

    public function __construct(UserPasswordHasherInterface $hasher, SluggerInterface $slugger, EntityManagerInterface $manager)
    {
        $this->hasher = $hasher;
        $this->slugger = $slugger;
        $this->manager = $manager;
    }

    /**
     * @Route("/", name="home")
     */
    public function index(TranslatorInterface $translator): Response
    {
        $faker = Factory::create();
/*        $user = $this->manager->getRepository(User::class)->findAll();
        $cat = $this->manager->getRepository(Category::class)->findAll();
        $tag = $this->manager->getRepository(Tags::class)->findAll();

        $length = rand(2, 10);
        for($i = 0; $i < $length; $i++) {
            $article = new Article();
            $article->setTitle($faker->realText(rand(10, 20)));
            $article->setIntro($faker->realText(rand(20, 100)));
            $article->setContent($faker->realText(rand(200, 300)));
            $article->setAuthor($user[rand(0, count($user) - 1)]);
            $article->setImage($faker->imageUrl());
            $article->setCreatedAt(new DateTimeImmutable('now'));
            $article->setUpdatedAt(new DateTimeImmutable('now'));
            $article->setSlug(strtolower($this->slugger->slug($article->getTitle())));

            for($i = 0; $i < rand(1, count($tag)); $i++) {
            $article->addTag($tag[rand(0, count($tag) - 1)]);
            }

            for($i = 0; $i < rand(1, count($cat)); $i++) {
            $article->addCategory($cat[rand(0, count($cat) - 1)]);
            }
            $this->manager->persist($article);
        }*/


/*        $user = $this->manager->getRepository(User::class)->findAll();

        $articles = $this->manager->getRepository(Article::class)->findAll();

        foreach ($articles as $article) {
            for($i = 0; $i < rand(1, 12); $i++) {
                $comment = new Comments();
                $comment->setArticle($article);
                $comment->setContent($faker->realText(rand(120, 300)));
                $comment->setUsername($user[rand(0, count($user) - 1)]);
                $comment->setUpdatedAt(new DateTimeImmutable('now'));

                $article->addComment($comment);
            }
            $this->manager->persist($article);
        }*/

        $title = $translator->trans('homepage.title', [], '', 'fr');

        $this->manager->flush();

        return $this->render('home/index.html.twig', [
            'title' => $title
        ]);
    }
}
