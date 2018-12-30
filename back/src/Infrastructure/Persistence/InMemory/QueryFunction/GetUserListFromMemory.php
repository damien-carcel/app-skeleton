<?php

declare(strict_types=1);

/*
 * This file is part of AppSkeleton.
 *
 * Copyright (c) 2018 Damien Carcel <damien.carcel@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Infrastructure\Persistence\InMemory\QueryFunction;

use App\Domain\Model\Read\UserList;
use App\Domain\Model\Write\User;
use App\Domain\QueryFunction\GetUserList;
use App\Domain\Repository\UserRepositoryInterface;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
class GetUserListFromMemory implements GetUserList
{
    /** @var UserRepositoryInterface */
    private $repository;

    /**
     * @param UserRepositoryInterface $repository
     */
    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(int $numberOfUsers, int $userPage): UserList
    {
        $persistedUsers = $this->repository->findAll();
        $usersToReturn = array_slice(
            $persistedUsers,
            $numberOfUsers * ($userPage - 1),
            $numberOfUsers * $userPage
        );

        return new UserList($this->normalizeUsers($usersToReturn));
    }

    /**
     * @param User[] $users
     *
     * @return array
     */
    private function normalizeUsers(array $users): array
    {
        return array_map(function (User $user) {
            return [
                'id' => (string) $user->id(),
                'username' => $user->getUsername(),
                'firstName' => $user->getFirstName(),
                'lastName' => $user->getLastName(),
            ];
        }, $users);
    }
}
