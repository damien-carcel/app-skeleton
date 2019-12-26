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

namespace Carcel\User\Infrastructure\Persistence\Doctrine\Repository;

use Carcel\User\Domain\Factory\UserFactory;
use Carcel\User\Domain\Model\Write\User;
use Carcel\User\Domain\Repository\UserRepositoryInterface;
use Doctrine\DBAL\Connection;
use Ramsey\Uuid\UuidInterface;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class UserRepository implements UserRepositoryInterface
{
    private $connection;
    private $factory;

    public function __construct(Connection $connection, UserFactory $factory)
    {
        $this->connection = $connection;
        $this->factory = $factory;
    }

    /**
     * {@inheritdoc}
     */
    public function findAll(): array
    {
        $statement = $this->connection->executeQuery('SELECT * FROM user');
        $result = $statement->fetchAll();

        return array_map(function (array $userData) {
            return $this->factory->create(
                $userData['id'],
                $userData['first_name'],
                $userData['last_name'],
                $userData['email'],
            );
        }, $result);
    }

    /**
     * {@inheritdoc}
     */
    public function find(UuidInterface $uuid): ?User
    {
        $query = <<<SQL
            SELECT id, email, first_name, last_name FROM user
            WHERE id = :id;
            SQL;
        $parameters = ['id' => (string) $uuid];
        $types = ['id' => \PDO::PARAM_STR];

        $statement = $this->connection->executeQuery($query, $parameters, $types);
        $result = $statement->fetchAll();

        if (empty($result)) {
            return null;
        }

        return $this->factory->create(
            $result[0]['id'],
            $result[0]['first_name'],
            $result[0]['last_name'],
            $result[0]['email'],
        );
    }

    /**
     * {@inheritdoc}
     */
    public function create(User $user): void
    {
        $this->connection->insert(
            'user',
            [
                'id' => (string) $user->id(),
                'first_name' => (string) $user->firstName(),
                'last_name' => (string) $user->lastName(),
                'email' => (string) $user->email(),
            ],
        );
    }

    /**
     * {@inheritdoc}
     */
    public function update(User $user): void
    {
        $this->connection->update(
            'user',
            [
                'first_name' => (string) $user->firstName(),
                'last_name' => (string) $user->lastName(),
                'email' => (string) $user->email(),
            ],
            ['id' => (string) $user->id()],
        );
    }

    /**
     * {@inheritdoc}
     */
    public function delete(User $user): void
    {
        $this->connection->delete('user', ['id' => (string) $user->id()]);
    }
}
