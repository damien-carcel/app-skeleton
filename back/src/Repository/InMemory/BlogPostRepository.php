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

use App\Domain\Model\BlogPost;
use App\Repository\BlogPostRepositoryInterface;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class BlogPostRepository implements BlogPostRepositoryInterface
{
    /** @var BlogPost[] */
    private $blogPosts;

    /**
     * {@inheritdoc}
     */
    public function getAllBlogPosts(): array
    {
        return array_values($this->blogPosts);
    }

    /**
     * @param string $uuid
     *
     * @return BlogPost
     */
    public function getOneById(string $uuid): BlogPost
    {
        return $this->blogPosts[$uuid];
    }

    /**
     * @param BlogPost $post
     */
    public function save(BlogPost $post): void
    {
        $this->blogPosts[$post->id()->toString()] = $post;
    }

    /**
     * @param BlogPost $post
     */
    public function delete(BlogPost $post): void
    {
        unset($this->blogPosts[$post->id()->toString()]);
    }
}
