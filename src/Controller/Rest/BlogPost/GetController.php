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

use App\Repository\BlogPostRepositoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 *
 * @Route("/api/blog/post/{uuid}", name="rest_blog_post_get", methods={"GET"})
 */
class GetController
{
    /** @var BlogPostRepositoryInterface */
    private $repository;

    /**
     * @param BlogPostRepositoryInterface $repository
     */
    public function __construct(BlogPostRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param string $uuid
     *
     * @throws NotFoundHttpException
     *
     * @return Response
     */
    public function __invoke(string $uuid): Response
    {
        $post = $this->repository->getOneById($uuid);

        if (null === $post) {
            throw new NotFoundHttpException(sprintf(
                'There is no blog post with identifier "%s"',
                $uuid
            ));
        }

        $normalizedPost =  [
            'id' => $post->id(),
            'title' => $post->title(),
            'content' => $post->content(),
        ];

        return new JsonResponse($normalizedPost);
    }
}
