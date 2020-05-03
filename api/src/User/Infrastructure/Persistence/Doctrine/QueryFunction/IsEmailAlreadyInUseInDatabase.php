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

use Carcel\User\Domain\QueryFunction\IsEmailAlreadyUsed;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Types;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class IsEmailAlreadyInUseInDatabase implements IsEmailAlreadyUsed
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function __invoke(string $email): bool
    {
        $query = <<<SQL
            SELECT EXISTS(
                SELECT 1 FROM user
                WHERE email = :email
            ) AS is_existing;
            SQL;
        $parameters = ['email' => $email];
        $types = ['email' => \PDO::PARAM_STR];

        $statement = $this->connection->executeQuery($query, $parameters, $types);
        $result = $statement->fetch();

        return $this->connection->convertToPHPValue($result['is_existing'], Types::BOOLEAN);
    }
}
