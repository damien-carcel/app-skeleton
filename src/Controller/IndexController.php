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
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 *
 * @Route("/", name="index")
 */
class IndexController
{
    /** @var UrlGeneratorInterface */
    private $urlGenerator;

    /**
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @return Response
     */
    public function __invoke(): Response
    {
        $listBlogPostsUrl = $this->urlGenerator->generate('rest_blog_posts_list');

        $html = <<<HTML
<html>
<body>
    <ul>
        <li>
            <a href="$listBlogPostsUrl">List all posts</a>
        </li>
    </ul>
</body>
</html>
HTML;

        return new Response($html);
    }
}
