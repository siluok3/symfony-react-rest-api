<?php

namespace App\DataFixtures;

use App\Entity\BlogPost;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /** @var UserPasswordEncoderInterface */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $this->loadUsers($manager);
        $this->loadBlogPosts($manager);
    }

    public function loadBlogPosts(ObjectManager $manager)
    {
        $user = $this->getReference('kiri_author');

        $blogPost =  new BlogPost();
        $blogPost->setTitle('Turing');
        $blogPost->setPublished(new \DateTime('2019-09-15 09:00:00'));
        $blogPost->setSlug('hey-you');
        $blogPost->setContent('That is a content');
        $blogPost->setAuthor($user);

        $manager->persist($blogPost);

        $blogPost =  new BlogPost();
        $blogPost->setTitle('Arximidis');
        $blogPost->setPublished(new \DateTime('2019-09-16 09:30:00'));
        $blogPost->setSlug('Que hace?');
        $blogPost->setContent('That is a content for ancient Greeks');
        $blogPost->setAuthor($user);

        $manager->persist($blogPost);

        $manager->flush();
    }

    public function loadComments(){}

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
