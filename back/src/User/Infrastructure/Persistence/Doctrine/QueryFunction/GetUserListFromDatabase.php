<?php

declare(strict_types=1);

/*
 * This file is part of AppSkeleton.
 *
 * Copyright (c) 2018 Damien Carcel <damien.carcel@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Carcel\User\Infrastructure\Persistence\Doctrine\QueryFunction;

use Carcel\User\Domain\Model\Read\UserList;
use Carcel\User\Domain\QueryFunction\GetUserList;
use Doctrine\DBAL\Connection;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
class GetUserListFromDatabase implements GetUserList
{
    /** @var Connection */
    private $connection;

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(int $numberOfUsers, int $userPage): UserList
    {
        $query = <<<SQL
SELECT id, username, first_name AS firstName, last_name AS lastName FROM user
LIMIT :limit OFFSET :offset;
SQL;
        $parameters = ['limit' => $numberOfUsers, 'offset' => ($userPage - 1) * $numberOfUsers];
        $types = ['limit' => \PDO::PARAM_INT, 'offset' => \PDO::PARAM_INT];

        $statement = $this->connection->executeQuery($query, $parameters, $types);
        $result = $statement->fetchAll();

        return new UserList($result);
    }
}
