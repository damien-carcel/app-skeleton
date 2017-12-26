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

namespace App\DataFixtures;

use App\Entity\BlogPost;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
class BlogPostFixtures extends Fixture
{
    private const NORMALIZED_POSTS = [
        [
            'title' => 'A first post',
            'content' => 'A very uninteresting content.'
        ],
        [
            'title' => 'Another post',
            'content' => 'Bla bla bla bla bla bla.'
        ],
        [
            'title' => 'And yet another',
            'content' => 'Still nothing interesting.'
        ]
    ];

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $objectManager): void
    {
        foreach (static::NORMALIZED_POSTS as $normalizedPost) {
            $post = new BlogPost();
            $post->update($normalizedPost);

            $objectManager->persist($post);
        }

        $objectManager->flush();
    }
}
