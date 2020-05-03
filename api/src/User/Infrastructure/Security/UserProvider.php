<?php

declare(strict_types=1);

/*
 * This file is part of app-skeleton.
 *
 * Copyright (c) 2018 Damien Carcel <damien.carcel@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Carcel\User\Infrastructure\Security;

use Carcel\User\Domain\QueryFunction\GetUserPassword;
use Doctrine\DBAL\Connection;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
class UserProvider implements UserProviderInterface, PasswordUpgraderInterface
{
    private GetUserPassword $getUserPassword;
    private Connection $connection;

    public function __construct(GetUserPassword $getUserPassword, Connection $connection)
    {
        $this->getUserPassword = $getUserPassword;
        $this->connection = $connection;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsClass(string $class): bool
    {
        return User::class === $class;
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByUsername(string $email): UserInterface
    {
        return $this->getSecurityUser($email);
    }

    /**
     * {@inheritdoc}
     */
    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Invalid user class "%s".', get_class($user)));
        }

        return $this->getSecurityUser($user->getUsername());
    }

    /**
     * {@inheritdoc}
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        $this->connection->update(
            'user',
            ['password' => $newEncodedPassword],
            ['email' => $user->getUsername()],
        );
    }

    private function getSecurityUser(string $email): UserInterface
    {
        $password = ($this->getUserPassword)($email);

        if (null === $password) {
            throw new UsernameNotFoundException($email);
        }

        return new User($email, $password, []);
    }
}
