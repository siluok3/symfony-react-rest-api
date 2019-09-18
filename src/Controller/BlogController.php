<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/blog")
 */
class BlogController extends AbstractController
{
    private const POSTS = [
        [
            'id' => 1,
            'slug' =>  'Kiriakos',
            'title' => 'Kalimera',
        ],
        [
            'id' => 2,
            'slug' =>  'Eleni',
            'title' => 'Kalispera',
        ],
    ];
    /**
     * @Route("/{page}", name="blog_list", defaults={"page": 1})
     */
    public function list(Request $request, $page)
    {
        $limit = $request->get('limit', 10);
        return $this->json([
            'page'  => $page,
            'limit' => $limit,
            'data'  => array_map( function($item) {
                return $this->generateUrl('blog_by_id', ['id' => $item['id']]);
            }, self::POSTS),
        ]);
    }

    /**
     * @Route("/{id}", name="blog_by_id", requirements={"id"="\d+"})
     */
    public function post($id)
    {
        return $this->json(
            self::POSTS[array_search($id, array_column(self::POSTS, 'id'))]
        );
    }

    /**
     * @Route("/{slug}", name="blog_by_slug")
     */
    public function postBySlug($slug)
    {
        return $this->json(
            self::POSTS[array_search($slug, array_column(self::POSTS, 'slug'))]
        );
    }
}