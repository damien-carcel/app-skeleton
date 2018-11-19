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

namespace App\Tests\Acceptance\Context;

use App\Domain\Model\BlogPost;
use App\Repository\InMemory\BlogPostRepository;
use App\Tests\Fixtures\BlogPostFixtures;
use Behat\Behat\Context\Context;
use Ramsey\Uuid\Uuid;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
class FixtureContext implements Context
{
    /** @var BlogPostRepository */
    private $inMemoryBlogPostRepository;

    /**
     * @param BlogPostRepository $inMemoryBlogPostRepository
     */
    public function __construct(BlogPostRepository $inMemoryBlogPostRepository)
    {
        $this->inMemoryBlogPostRepository = $inMemoryBlogPostRepository;
    }

    /**
     * @BeforeScenario
     */
    public function loadFixturesWithOnlyUsers(): void
    {
        $blogPosts = BlogPostFixtures::NORMALIZED_POSTS;

        foreach ($blogPosts as $post) {
            $post['id'] = Uuid::uuid4();

            $postEntity = new BlogPost();
            $postEntity->update($post);

            $this->inMemoryBlogPostRepository->save($postEntity);
        }
    }
}
