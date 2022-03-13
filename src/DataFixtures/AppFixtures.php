<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comments;
use App\Entity\Tags;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;
    private SluggerInterface $slugger;

    public function __construct(UserPasswordHasherInterface $hasher, SluggerInterface $slugger)
    {
        $this->hasher = $hasher;
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        //Categories
        $catData = ['SPORT', 'NEWS', 'GAMES', 'TRAVELS', 'FOOD', 'POP', 'HIGHTECH'];
        for ($i = 0; $i < count($catData); $i++) {
            $cat = new Category();
            $cat->setTitle($catData[$i]);

            $manager->persist($cat);
        }

        //Tags
        $tagData = ['trip', 'hobbies', 'foody', 'foodie', 'sports', 'extreme', 'geek', 'popculture', 'fun', 'enjoy'];
        for ($i = 0; $i < count($tagData); $i++) {
            $tag = new Tags();
            $tag->setTitle($tagData[$i]);

            $manager->persist($tag);
        }

        //USER
        for($i = 0; $i < 8; $i++) {
            $user = new User();
            $user->setEmail($faker->email);
            $user->setUsername($faker->userName);
            $user->setRoles(['ROLE_USER']);
            $user->setPassword($this->hasher->hashPassword($user, '123456'));
            $user->setCreatedAt(new DateTimeImmutable());
            $manager->persist($user);

            $length = rand(1, 6);
            for($i = 0; $i < $length; $i++) {
                $article = new Article();
                $article->setTitle($faker->realText(rand(10, 20)));
                $article->setIntro($faker->realText(rand(20, 100)));
                $article->setContent($faker->realText(rand(200, 300)));
                $article->setAuthor($user);
                $article->setImage($faker->imageUrl());
                $article->setCreatedAt(new DateTimeImmutable());
                $article->setUpdatedAt(new DateTimeImmutable());
                $article->setSlug($this->slugger->slug($article->getTitle()));

              /*  for($i = 0; $i < rand(1, count($tagData)); $i++) {
                    $article->addTag($tag[rand(0, count($tagData))]);
                }

                for($i = 0; $i < rand(1, count($catData)); $i++) {
                    $article->addCategory($cat[rand(0, count($catData))]);
                }*/

                $manager->persist($article);
            }

            $manager->flush();
        }
    }
}


