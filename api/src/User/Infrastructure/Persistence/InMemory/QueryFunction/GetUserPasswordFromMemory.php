<?php

declare(strict_types=1);

/*
 * This file is part of app-skeleton.
 *
 * Copyright (c) 2020 Damien Carcel <damien.carcel@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Carcel\User\Infrastructure\Persistence\InMemory\QueryFunction;

use Carcel\User\Domain\Model\Write\User;
use Carcel\User\Domain\QueryFunction\GetUserPassword;
use Carcel\User\Domain\Repository\UserRepositoryInterface;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class GetUserPasswordFromMemory implements GetUserPassword
{
    private UserRepositoryInterface $repository;

    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * As email is unique, the filtering should always return only one user.
     */
    public function __invoke(string $email): ?string
    {
        $users = $this->repository->findAll();

        $filteredUsers = array_filter($users, function (User $user) use ($email) {
            return (string) $user->email() === $email;
        });

        if (empty($filteredUsers)) {
            return null;
        }

        $user = reset($filteredUsers);

        return (string) $user->password();
    }
}
