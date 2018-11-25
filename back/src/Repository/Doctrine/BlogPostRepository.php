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

namespace App\Repository\Doctrine;

use App\Domain\Model\BlogPost;
use App\Domain\Repository\BlogPostRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class BlogPostRepository extends ServiceEntityRepository implements BlogPostRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, BlogPost::class);
    }

    /**
     * {@inheritdoc}
     */
    public function getAllBlogPosts(): array
    {
        return $this->findAll();
    }

    /**
     * {@inheritdoc}
     */
    public function getOneById(string $uuid): BlogPost
    {
        return $this->find($uuid);
    }

    /**
     * {@inheritdoc}
     */
    public function save(BlogPost $post): void
    {
        $this->getEntityManager()->persist($post);
        $this->getEntityManager()->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function delete(BlogPost $post): void
    {
        $this->getEntityManager()->remove($post);
        $this->getEntityManager()->flush();
    }
}
