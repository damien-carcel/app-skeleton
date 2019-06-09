<?php

declare(strict_types=1);

/*
 * This file is part of AppSkeleton.
 *
 * Copyright (c) 2019 Damien Carcel <damien.carcel@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Carcel\User\Domain\Factory;

use Carcel\User\Domain\Model\Write\User;
use Ramsey\Uuid\Uuid;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class UserFactory
{
    /**
     * @param array $userData
     *
     * @throws \Exception
     *
     * @return User
     */
    public function create(array $userData): User
    {
        if (!isset($userData['id']) || empty($userData['id'])) {
            $userData['id'] = Uuid::uuid4();
        }

        if (is_string($userData['id'])) {
            if (!Uuid::isValid($userData['id'])) {
                throw new \InvalidArgumentException(sprintf(
                    'Provided user ID "%s" is not a valid UUID.',
                    $userData['id']
                ));
            }
            $userData['id'] = Uuid::fromString($userData['id']);
        }

        return new User(
            $userData['id'],
            $userData['username'],
            $userData['firstName'],
            $userData['lastName'],
            $userData['password'],
            $userData['salt'],
            $userData['roles']
        );
    }
}
