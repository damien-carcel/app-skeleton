<?php

/*
 * This file is part of AppSkeleton.
 *
 * Copyright (c) 2017 Damien Carcel <damien.carcel@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller;

use App\Entity\BlogPost;
use App\Repository\BlogPostRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
class IndexController
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
     * @return Response
     */
    public function __invoke(): Response
    {
        $posts = $this->repository->findAll();

        $normalizedPosts = array_map(function (BlogPost $post) {
            return [
                'id' => $post->id(),
                'title' => $post->title(),
                'content' => $post->content(),
            ];
        }, $posts);

        return new JsonResponse($normalizedPosts);
    }
}
