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

use App\Repository\BlogPostRepositoryInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
class IndexController
{
    /** @var BlogPostRepositoryInterface */
    private $repository;

    /** @var \Twig_Environment */
    private $twig;

    /**
     * @param BlogPostRepositoryInterface $repository
     * @param \Twig_Environment           $twig
     */
    public function __construct(BlogPostRepositoryInterface $repository, \Twig_Environment $twig)
    {
        $this->repository = $repository;
        $this->twig = $twig;
    }

    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     *
     * @return Response
     */
    public function __invoke(): Response
    {
        $posts = $this->repository->getAllBlogPosts();

        $content = $this->twig->render('app/app.html.twig', ['posts' => $posts]);

        $response = new Response();
        $response->setContent($content);

        return $response;
    }
}