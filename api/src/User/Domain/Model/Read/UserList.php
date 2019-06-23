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

namespace Carcel\User\Domain\Model\Read;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class UserList
{
    private $users;

    public function __construct(array $usersData)
    {
        $this->users = array_map(function (array $userData) {
            return new User(
                $userData['id'],
                $userData['username'],
                $userData['firstName'],
                $userData['lastName']
            );
        }, $usersData);
    }

    public function normalize(): array
    {
        $normalizedUsers = array_map(function (User $user) {
            return $user->normalize();
        }, $this->users);

        return $normalizedUsers;
    }
}
