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

namespace App\Repository;

use App\Domain\Model\BlogPost;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
interface BlogPostRepositoryInterface
{
    /**
     * @return BlogPost[]
     */
    public function getAllBlogPosts(): array;

    /**
     * @param string $uuid
     *
     * @return BlogPost
     */
    public function getOneById(string $uuid): BlogPost;

    /**
     * @param BlogPost $post
     */
    public function save(BlogPost $post): void;

    /**
     * @param BlogPost $post
     */
    public function delete(BlogPost $post): void;
}
