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
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class User
{
    private $id;
    private $firstName;
    private $lastName;
    private $email;

    public function __construct(
        UuidInterface $id,
        FirstName $firstName,
        LastName $lastName,
        Email $email
    ) {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
    }

    public function id(): UuidInterface
    {
        return $this->id;
    }

    public function firstName(): FirstName
    {
        return $this->firstName;
    }

    public function lastName(): LastName
    {
        return $this->lastName;
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function update(FirstName $firstName, LastName $lastName, Email $email): void
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
    }
}
