<?php

namespace App\DataFixtures;

use App\Entity\BlogPost;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $blogPost =  new BlogPost();
        $blogPost->setTitle('Turing');
        $blogPost->setPublished(new \DateTime('2019-09-15 09:00:00'));
        $blogPost->setSlug('hey-you');
        $blogPost->setContent('That is a content');
        $blogPost->setAuthor('Kiri Pap');

        $manager->persist($blogPost);

        $blogPost =  new BlogPost();
        $blogPost->setTitle('Arximidis');
        $blogPost->setPublished(new \DateTime('2019-09-16 09:30:00'));
        $blogPost->setSlug('Que hace?');
        $blogPost->setContent('That is a content for ancient Greeks');
        $blogPost->setAuthor('siluok3');

        $manager->persist($blogPost);

        $manager->flush();
    }
}
