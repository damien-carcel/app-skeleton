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

namespace App\Tests\Integration\Context;

use App\Domain\Model\BlogPost;
use App\Domain\Repository\BlogPostRepositoryInterface;
use App\Tests\Fixtures\BlogPostFixtures;
use Behat\Behat\Context\Context;
use Ramsey\Uuid\Uuid;
use Webmozart\Assert\Assert;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
class FeatureContext implements Context
{
    /** @var BlogPostRepositoryInterface */
    private $doctrineBlogPostRepository;

    /** @var array */
    private $result;

    /**
     * @param BlogPostRepositoryInterface $doctrineBlogPostRepository
     */
    public function __construct(BlogPostRepositoryInterface $doctrineBlogPostRepository)
    {
        $this->doctrineBlogPostRepository = $doctrineBlogPostRepository;
    }

    /**
     * @param string $methodName
     *
     * @When the ":methodName" method from the Doctrine BlogPostRepository is called
     */
    public function callGetMethodFromRepository(string $methodName): void
    {
        $this->result = $this->doctrineBlogPostRepository->$methodName();
    }

    /**
     * @Then all the blog posts should be retrieved from database
     */
    public function allBlogPostsAreRetrieved(): void
    {
        Assert::count($this->result, 3);

        $normalizedBlogPosts = [];
        foreach ($this->result as $blogPost) {
            Assert::isInstanceOf($blogPost, BlogPost::class);
            Assert::isInstanceOf($blogPost->id(), Uuid::class);

            $normalizedBlogPosts[] = [
                'title' => $blogPost->title(),
                'content' => $blogPost->content(),
            ];
        }

        Assert::allOneOf($normalizedBlogPosts, BlogPostFixtures::NORMALIZED_POSTS);
    }
}
