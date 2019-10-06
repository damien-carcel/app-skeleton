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

namespace Carcel\User\Infrastructure\Persistence\Doctrine\QueryFunction;

use Carcel\User\Domain\Model\Read\User;
use Carcel\User\Domain\QueryFunction\GetUser;
use Doctrine\DBAL\Connection;
use Ramsey\Uuid\UuidInterface;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class GetUserFromDatabase implements GetUser
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(UuidInterface $uuid): User
    {
        $query = <<<SQL
SELECT id, username, first_name AS firstName, last_name AS lastName FROM user
WHERE id = :id;
SQL;
        $parameters = ['id' => $uuid->toString()];
        $types = ['id' => \PDO::PARAM_STR];

        $statement = $this->connection->executeQuery($query, $parameters, $types);
        $result = $statement->fetchAll();

        return new User(
            $result[0]['id'],
            $result[0]['username'],
            $result[0]['firstName'],
            $result[0]['lastName']
        );
    }
}
