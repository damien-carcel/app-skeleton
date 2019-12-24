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
final class UpdateUserData
{
    private $identifier;
    private $email;
    private $firstName;
    private $lastName;

    public function __construct(string $identifier, string $email, string $firstName, string $lastName)
    {
        $this->identifier = $identifier;
        $this->email = $email;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }

    public function identifier(): string
    {
        return $this->identifier;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function firstName(): string
    {
        return $this->firstName;
    }

    public function lastName(): string
    {
        return $this->lastName;
    }
}
