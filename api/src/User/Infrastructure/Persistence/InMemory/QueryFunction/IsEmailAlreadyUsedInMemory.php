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
use Carcel\User\Domain\QueryFunction\IsEmailAlreadyUsed;
use Carcel\User\Domain\Repository\UserRepository;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class IsEmailAlreadyUsedInMemory implements IsEmailAlreadyUsed
{
    private UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(string $email): bool
    {
        $users = $this->repository->findAll();

        $filteredUsers = array_filter($users, function (User $user) use ($email) {
            return (string) $user->email() === $email;
        });

        if (empty($filteredUsers)) {
            return false;
        }

        return true;
    }
}
