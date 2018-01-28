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

use App\Entity\BlogPost;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
interface BlogPostRepositoryInterface
{
    /**
     * @return BlogPost[]
     */
    public function getAllBlogPosts(): array;
}
