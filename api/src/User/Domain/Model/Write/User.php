<?php

declare(strict_types=1);

/*
 * This file is part of AppSkeleton.
 *
 * Copyright (c) 2017 Damien Carcel <damien.carcel@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Carcel\User\Domain\Model\Write;

use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * This class cannot be final because of Doctrine ORM….
 *
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
class User implements UserInterface
{
    private $id;
    private $username;
    private $firstName;
    private $lastName;
    private $password;
    private $salt;
    private $roles;

    public function __construct(
        UuidInterface $id,
        string $username,
        string $firstName,
        string $lastName,
        string $password,
        ?string $salt,
        array $roles
    ) {
        $this->id = $id;
        $this->username = $username;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->password = $password;
        $this->salt = $salt;
        $this->roles = $roles;
    }

    public function id(): UuidInterface
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * {@inheritdoc}
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * {@inheritdoc}
     */
    public function getSalt(): ?string
    {
        return $this->salt;
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials(): void
    {
        throw new \LogicException('Not implemented yet.');
    }

    public function changeName(array $data): void
    {
        if (array_key_exists('firstName', $data)) {
            $this->firstName = $data['firstName'];
        }

        if (array_key_exists('lastName', $data)) {
            $this->lastName = $data['lastName'];
        }
    }
}
