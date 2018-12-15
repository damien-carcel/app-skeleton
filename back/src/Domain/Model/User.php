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

namespace App\Domain\Model;

use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
class User implements UserInterface
{
    /** @var UuidInterface */
    private $id;

    /** @var string */
    private $username;

    /** @var string */
    private $firstName;

    /** @var string */
    private $lastName;

    /** @var string */
    private $password;

    /** @var string|null */
    private $salt;

    /** @var array */
    private $roles;

    /**
     * @param UuidInterface $id
     * @param string        $username
     * @param string        $firstName
     * @param string        $lastName
     * @param string        $password
     * @param string|null   $salt
     * @param array         $roles
     */
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

    /**
     * @return UuidInterface
     */
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

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
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
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * @param array $data
     */
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
