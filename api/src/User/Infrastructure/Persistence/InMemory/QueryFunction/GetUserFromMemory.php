<?php

declare(strict_types=1);

/*
 * This file is part of app-skeleton.
 *
 * Copyright (c) 2019 Damien Carcel <damien.carcel@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Carcel\User\Infrastructure\Persistence\InMemory\QueryFunction;

use Carcel\User\Domain\Model\Read;
use Carcel\User\Domain\Model\Write;
use Carcel\User\Domain\QueryFunction\GetUser;
use Carcel\User\Domain\Repository\UserRepositoryInterface;
use Ramsey\Uuid\UuidInterface;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class GetUserFromMemory implements GetUser
{
    private UserRepositoryInterface $repository;

    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function byId(UuidInterface $uuid): ?Read\User
    {
        $user = $this->repository->find($uuid);

        if (null === $user) {
            return null;
        }

        return new Read\User(
            $user->id()->toString(),
            (string) $user->firstName(),
            (string) $user->lastName(),
            (string) $user->email(),
        );
    }

    /**
     * As email is unique, the filtering should always return only one user.
     */
    public function byEmail(string $email): ?array
    {
        $users = $this->repository->findAll();

        $filteredUsers = array_filter($users, function (Write\User $user) use ($email) {
            return (string) $user->email() === $email;
        });

        if (empty($filteredUsers)) {
            return null;
        }

        $user = reset($filteredUsers);

        return [
            'email' => (string) $user->email(),
            'password' => (string) $user->password(),
        ];
    }
}
