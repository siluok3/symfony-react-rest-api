<?php

namespace App\Controller;

use App\Entity\BlogPost;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/blog")
 */
class BlogController extends AbstractController
{
    /**
     * @Route("/{page}", name="blog_list", defaults={"page": 1}, requirements={"page"="\d+"})
     */
    public function list(Request $request, $page)
    {
        $limit = $request->get('limit', 10);
        $blogPosts = $this->getDoctrine()->getRepository(BlogPost::class)->findAll();

        return $this->json([
            'page'  => $page,
            'limit' => $limit,
            'data'  => array_map( function(BlogPost $item) {
                return $this->generateUrl('blog_by_id', ['id' => $item->getSlug()]);
            }, $blogPosts),
        ]);
    }

    /**
     * @Route("/post/{id}", name="blog_by_id", requirements={"id"="\d+"}, methods={"GET"})
     * @ParamConverter("blogPost", class="App:BlogPost")
     */
    public function post($blogPost)
    {
        return $this->json($blogPost);
    }

    /**
     * @Route("/post/{slug}", name="blog_by_slug", methods={"GET"})
     * @ParamConverter("blogPost", class="App:BlogPost", options={"mapping": {"slug": "slug"}})
     */
    public function postBySlug($blogPost)
    {
        return $this->json($blogPost);
    }

    /**
     * @Route("/add", name="blog_add", methods={"POST"})
     */
    public function add(Request $request)
    {
        /** @var Serializer $serializer */
        $serializer = $this->get('serializer');

        $blogPost = $serializer->deserialize($request->getContent(), BlogPost::class, 'json');

        $em = $this->getDoctrine()->getManager();
        $em->persist($blogPost);
        $em->flush();

        return $this->json($blogPost);
    }

    /**
     * @Route("/post/{id}", name="blog_delete", methods={"DELETE"})
     */
    public function delete(BlogPost $blogPost)
    {
        $em = $this->getDoctrine()->getManager();
        
        $em->remove($blogPost);
        $em->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}