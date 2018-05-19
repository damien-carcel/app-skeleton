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

namespace App\Tests\System\Context;

use App\Tests\Fixtures\BlogPostFixtures;
use Behat\MinkExtension\Context\MinkContext;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
class FeatureContext extends MinkContext implements KernelAwareContext
{
    /** @var KernelInterface */
    private $kernel;

    /**
     * {@inheritdoc}
     */
    public function setKernel(KernelInterface $kernel): void
    {
        $this->kernel = $kernel;
    }

    /**
     * @When a request asks for the list of blog posts
     */
    public function listAllTheBlogPosts(): void
    {
        $router = $this->kernel->getContainer()->get('router');

        $this->visitPath($router->generate('rest_blog_posts_list'));
    }

    /**
     * @return bool
     *
     * @Then all the blog posts should be retrieved
     */
    public function allBlogPostsShouldBeRetrieved(): bool
    {
        $responseContent = $this->getSession()->getPage()->getContent();
        $decodedContent = json_decode($responseContent, true);

        $filteredContent = array_filter($decodedContent, function ($post) {
            unset($post['id']);

            return $post;
        });

        if (BlogPostFixtures::NORMALIZED_POSTS !== $filteredContent) {
            return false;
        }

        return true;
    }
}
