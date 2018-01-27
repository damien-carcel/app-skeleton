<?php

declare(strict_types=1);

/*
 * This file is part of SymfonySkeleton.
 *
 * Copyright (c) 2018 Damien Carcel <damien.carcel@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Repository\InMemory;

use App\Entity\BlogPost;
use App\Repository\BlogPostRepositoryInterface;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
class BlogPostRepository implements BlogPostRepositoryInterface
{
    /** @var BlogPost[] */
    private $blogPosts;

    /**
     * {@inheritdoc}
     */
    public function getAllBlogPosts(): array
    {
        return $this->blogPosts;
    }

    /**
     * @param BlogPost $blogPost
     */
    public function addBlogPost(BlogPost $blogPost): void
    {
        $this->blogPosts[] = $blogPost;
    }
}
