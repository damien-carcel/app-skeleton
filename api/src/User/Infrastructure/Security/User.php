<?php

/*
 * This file is part of app-skeleton.
 *
 * Copyright (c) 2018 Damien Carcel <damien.carcel@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Carcel\User\Infrastructure\Security;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
class User implements UserInterface
{
    private string $email;
    private string $password;
    private array $roles;

    public function __construct()
    {
        $this->roles = [];
    }

    /**
     * {@inheritdoc}
     */
    public function getUsername(): string
    {
        return $this->email;
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * {@inheritdoc}
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    /**
     * {@inheritdoc}
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}
