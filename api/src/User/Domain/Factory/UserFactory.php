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

namespace Carcel\User\Domain\Factory;

use Carcel\User\Domain\Model\Write\Email;
use Carcel\User\Domain\Model\Write\FirstName;
use Carcel\User\Domain\Model\Write\LastName;
use Carcel\User\Domain\Model\Write\User;
use Ramsey\Uuid\Uuid;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class UserFactory
{
    public function create(string $id, string $firstName, string $lastName, string $email): User
    {
        return new User(
            Uuid::fromString($id),
            FirstName::fromString($firstName),
            LastName::fromString($lastName),
            Email::fromString($email),
        );
    }
}
