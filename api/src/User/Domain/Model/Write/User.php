<?php

declare(strict_types=1);

/*
 * This file is part of app-skeleton.
 *
 * Copyright (c) 2017 Damien Carcel <damien.carcel@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Carcel\User\Domain\Model\Write;

use Ramsey\Uuid\UuidInterface;

/**
 * This class cannot be final because of Doctrine ORM….
 *
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
class User
{
    private $id;
    private $email;
    private $firstName;
    private $lastName;

    public function __construct(
        UuidInterface $id,
        Email $email,
        FirstName $firstName,
        string $lastName
    ) {
        $this->id = $id;
        $this->email = $email;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }

    public function id(): UuidInterface
    {
        return $this->id;
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function firstName(): FirstName
    {
        return $this->firstName;
    }

    public function lastName(): string
    {
        return $this->lastName;
    }

    public function update(Email $email, FirstName $firstName, string $lastName): void
    {
        $this->email = $email;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }
}
