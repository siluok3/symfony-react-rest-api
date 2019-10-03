<?php

namespace App\DataFixtures;

use App\Entity\BlogPost;
use App\Entity\Comment;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /** @var UserPasswordEncoderInterface */
    private $encoder;

    /** \Faker\Factory */
    private $faker;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
        $this->faker   = \Faker\Factory::create();
    }

    public function load(ObjectManager $manager)
    {
        $this->loadUsers($manager);
        $this->loadBlogPosts($manager);
        $this->loadComments($manager);
    }

    public function loadBlogPosts(ObjectManager $manager)
    {
        for ($i = 0; $i < 20; $i++) {
            $blogPost = new BlogPost();
            $blogPost->setTitle($this->faker->realText(30));
            $blogPost->setPublished($this->faker->dateTimeThisYear);
            $blogPost->setSlug($this->faker->slug);
            $blogPost->setContent($this->faker->realText());
            $blogPost->setAuthor($this->getReference('kiri_author'));

            $manager->persist($blogPost);
        }

        $manager->flush();
    }

    public function loadComments(ObjectManager $manager)
    {
        for ($i = 0; $i < 100; $i++) {
            // Get random number of comments for each blog post
            for ($j = 0; $j < rand(1, 5); $j++) {
                $comment = new Comment();
                $comment->setPublished($this->faker->dateTimeThisYear);
                $comment->setContent($this->faker->realText());
                $comment->setAuthor($this->getReference('kiri_author'));

                $this->setReference('comment_'.$i, $comment);

                $manager->persist($comment);
            }
        }

        $manager->flush();
    }

    public function loadUsers(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('siluok3');
        $user->setEmail('kiripap@gmail.com');
        $user->setName('Kiriakos Papachristou');
        $user->setPassword($this->encoder->encodePassword(
            $user,
            'verysafepassword'
        ));

        $this->addReference('kiri_author', $user);

        $manager->persist($user);
        $manager->flush();
    }
}
