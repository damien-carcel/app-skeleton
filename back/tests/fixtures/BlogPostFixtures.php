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

namespace App\Tests\Fixtures;

use App\Domain\Model\BlogPost;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
class BlogPostFixtures extends Fixture
{
    public const NORMALIZED_POSTS = [
        [
            'title' => 'A first post',
            'content' => 'A very uninteresting content.',
        ],
        [
            'title' => 'Another post',
            'content' => 'Bla bla bla bla bla bla.',
        ],
        [
            'title' => 'And yet another',
            'content' => 'Still nothing interesting.',
        ],
    ];

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $objectManager): void
    {
        foreach (static::NORMALIZED_POSTS as $normalizedPost) {
            $post = new BlogPost(Uuid::uuid4(), $normalizedPost['title'], $normalizedPost['content']);

            $objectManager->persist($post);
        }

        $objectManager->flush();
    }
}
