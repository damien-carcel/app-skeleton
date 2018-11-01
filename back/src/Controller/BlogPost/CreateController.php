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

use App\Entity\BlogPost;
use App\Repository\BlogPostRepositoryInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 *
 * @Route("/posts", name="rest_blog_posts_create", methods={"POST"})
 */
final class CreateController
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
     * @param Request $request
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function __invoke(Request $request): Response
    {
        $content = $request->getContent();
        $postData = json_decode($content, true);

        $post = new BlogPost(Uuid::uuid4(), $postData['title'], $postData['content']);

        $this->repository->save($post);

        return new JsonResponse();
    }
}
