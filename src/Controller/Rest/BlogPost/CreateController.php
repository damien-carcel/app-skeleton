<?php

declare(strict_types=1);

/*
 * This file is part of AppSkeleton.
 *
 * Copyright (c) 2017 Damien Carcel <damien.carcel@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller\Rest\BlogPost;

use App\Entity\BlogPost;
use App\Repository\BlogPostRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 *
 * @Route("/rest/blog/post/create", name="rest_blog_post_create")
 */
class CreateController
{
    /** @var BlogPostRepository */
    private $repository;

    /**
     * @param BlogPostRepository $repository
     */
    public function __construct(BlogPostRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param Request $request
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @return Response
     */
    public function __invoke(Request $request): Response
    {
        $content = $request->getContent();
        $postData = json_decode($content, true);

        $post = new BlogPost();
        $post->update($postData);

        $this->repository->save($post);

        $response = new JsonResponse();
        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response;
    }
}
