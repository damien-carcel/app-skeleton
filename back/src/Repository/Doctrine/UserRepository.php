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

use App\Domain\Model\User;
use App\Domain\Repository\UserRepositoryInterface;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class UserRepository implements UserRepositoryInterface
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritdoc}
     */
    public function findAll(): array
    {
        return $this->getDoctrineRepository()->findAll();
    }

    /**
     * {@inheritdoc}
     */
    public function find(string $uuid): ?User
    {
        return $this->getDoctrineRepository()->find($uuid);
    }

    /**
     * {@inheritdoc}
     */
    public function save(User $user): void
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function delete(User $user): void
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }

    /**
     * @return ObjectRepository
     */
    private function getDoctrineRepository(): ObjectRepository
    {
        return $this->entityManager->getRepository(User::class);
    }
}
