<?php

declare(strict_types=1);

/*
 * This file is part of app-skeleton.
 *
 * Copyright (c) 2020 Damien Carcel <damien.carcel@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Carcel\User\Infrastructure\Persistence\Doctrine\QueryFunction;

use Carcel\User\Domain\QueryFunction\GetUserPassword;
use Doctrine\DBAL\Connection;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class GetUserPasswordFromDatabase implements GetUserPassword
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function __invoke(string $email): ?string
    {
        $query = <<<SQL
            SELECT password FROM user
            WHERE email = :email;
            SQL;
        $parameters = ['email' => $email];
        $types = ['email' => \PDO::PARAM_STR];

        $statement = $this->connection->executeQuery($query, $parameters, $types);
        $result = $statement->fetchAll();

        if (empty($result)) {
            return null;
        }

        return $result[0]['password'];
    }
}
