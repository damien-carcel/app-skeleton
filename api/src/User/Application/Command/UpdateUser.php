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

namespace Carcel\User\Application\Command;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class UpdateUser
{
    public function __construct(
        private string $identifier,
        private string $firstName,
        private string $lastName,
        private string $email
    ) {
    }

    public function identifier(): string
    {
        return $this->identifier;
    }

    public function firstName(): string
    {
        return $this->firstName;
    }

    public function lastName(): string
    {
        return $this->lastName;
    }

    public function email(): string
    {
        return $this->email;
    }
}
