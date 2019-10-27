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

use Carcel\User\Domain\Model\Read\User;
use Carcel\User\Domain\QueryFunction\GetUser;
use Carcel\User\Domain\Repository\UserRepositoryInterface;
use Ramsey\Uuid\UuidInterface;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class GetUserFromMemory implements GetUser
{
    private $repository;

    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(UuidInterface $uuid): ?User
    {
        $user = $this->repository->find($uuid->toString());

        if (null === $user) {
            return null;
        }

        return new User(
            $user->id()->toString(),
            $user->email(),
            $user->firstName(),
            $user->lastName(),
        );
    }
}
