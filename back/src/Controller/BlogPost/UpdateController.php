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

namespace App\Controller\BlogPost;

use App\Repository\BlogPostRepositoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 *
 * @Route("/posts/{uuid}", name="rest_blog_posts_update", methods={"PATCH"})
 */
class UpdateController
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
     * @param string  $uuid
     * @param Request $request
     *
     * @return Response
     */
    public function __invoke(string $uuid, Request $request): Response
    {
        $content = $request->getContent();
        $postData = json_decode($content, true);

        $post = $this->repository->getOneById($uuid);
        if (null === $post) {
            throw new NotFoundHttpException(sprintf(
                'There is no blog post with identifier "%s"',
                $uuid
            ));
        }
        $post->update($postData);

        $this->repository->save($post);

        return new JsonResponse();
    }
}
