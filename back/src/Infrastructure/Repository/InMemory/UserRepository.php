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

namespace App\Infrastructure\Repository\InMemory;

use App\Domain\Model\Write\User;
use App\Domain\Repository\UserRepositoryInterface;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class UserRepository implements UserRepositoryInterface
{
    /** @var User[] */
    private $users;

    /**
     * @param User[] $users
     */
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
     * @param string $uuid
     *
     * @return User|null
     */
    public function find(string $uuid): ?User
    {
        if (!array_key_exists($uuid, $this->users)) {
            return null;
        }

        return $this->users[$uuid];
    }

    /**
     * @param User $user
     */
    public function save(User $user): void
    {
        $this->users[$user->id()->toString()] = $user;
    }

    /**
     * @param User $user
     */
    public function delete(User $user): void
    {
        unset($this->users[$user->id()->toString()]);
    }
}
