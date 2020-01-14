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

namespace Carcel\User\Infrastructure\Persistence\InMemory\Repository;

use Carcel\User\Domain\Model\Write\User;
use Carcel\User\Domain\Repository\UserRepositoryInterface;
use Ramsey\Uuid\UuidInterface;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class UserRepository implements UserRepositoryInterface
{
    /** @var User[] */
    private array $users;

    public function __construct(array $users = [])
    {
        foreach ($users as $user) {
            $this->save($user);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function findAll(): array
    {
        return array_values($this->users);
    }

    /**
     * {@inheritdoc}
     */
    public function find(UuidInterface $uuid): ?User
    {
        if (!array_key_exists((string) $uuid, $this->users)) {
            return null;
        }

        return $this->users[(string) $uuid];
    }

    /**
     * {@inheritdoc}
     */
    public function create(User $user): void
    {
        $this->save($user);
    }

    /**
     * {@inheritdoc}
     */
    public function update(User $user): void
    {
        $this->save($user);
    }

    /**
     * {@inheritdoc}
     */
    public function delete(User $user): void
    {
        unset($this->users[(string) $user->id()]);
    }

    private function save(User $user): void
    {
        $this->users[(string) $user->id()] = $user;
    }
}
