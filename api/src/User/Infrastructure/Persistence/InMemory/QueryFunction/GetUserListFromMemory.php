<?php

declare(strict_types=1);

/*
 * This file is part of app-skeleton.
 *
 * Copyright (c) 2018 Damien Carcel <damien.carcel@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Carcel\User\Infrastructure\Persistence\InMemory\QueryFunction;

use Carcel\User\Domain\Model\Read\UserList;
use Carcel\User\Domain\Model\Write\User;
use Carcel\User\Domain\QueryFunction\GetUserList;
use Carcel\User\Domain\Repository\UserRepositoryInterface;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class GetUserListFromMemory implements GetUserList
{
    private $repository;

    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(int $numberOfUsers, int $userPage): UserList
    {
        $persistedUsers = $this->repository->findAll();
        $usersToReturn = array_slice(
            $persistedUsers,
            $numberOfUsers * ($userPage - 1),
            $numberOfUsers
        );

        return new UserList($this->normalizeUsers($usersToReturn));
    }

    private function normalizeUsers(array $users): array
    {
        return array_map(function (User $user) {
            return [
                'id' => (string) $user->id(),
                'email' => (string) $user->email(),
                'firstName' => (string) $user->firstName(),
                'lastName' => (string) $user->lastName(),
            ];
        }, $users);
    }
}
