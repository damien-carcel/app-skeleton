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

use App\Repository\BlogPostRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 *
 * @Route("/api/blog/post/{uuid}/delete", name="rest_blog_post_delete")
 */
class DeleteController
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
     * @param string $uuid
     *
     * @throws NotFoundHttpException
     * @throws \Doctrine\ORM\ORMException
     *
     * @return Response
     */
    public function __invoke(string $uuid): Response
    {
        $post = $this->repository->find($uuid);

        if (null === $post) {
            throw new NotFoundHttpException(sprintf(
                'There is no blog post with identifier "%s"',
                $uuid
            ));
        }

        $this->repository->delete($post);

        return new JsonResponse();
    }
}
