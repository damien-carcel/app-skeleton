<?php

declare(strict_types=1);

/*
 * This file is part of app-skeleton.
 *
 * Copyright (c) 2019 Damien Carcel <damien.carcel@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Carcel\Tests\EndToEnd\Context;

use Behat\MinkExtension\Context\RawMinkContext;
use Carcel\Tests\Fixtures\UserFixtures;
use Doctrine\DBAL\Driver\Connection;
use Symfony\Component\Routing\RouterInterface;
use Webmozart\Assert\Assert;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class UpdateUserContext extends RawMinkContext
{
    private const USER_DATA_TO_UPDATE = [
        'firstName' => 'Peter',
        'lastName' => 'Parker',
    ];

    private $router;
    private $connection;

    private $updatedUserIdentifier;

    public function __construct(RouterInterface $router, Connection $connection)
    {
        $this->router = $router;
        $this->connection = $connection;
    }

    /**
     * @When I change the name of an existing user
     */
    public function changeTheNameOfAnExistingUser(): void
    {
        $this->updatedUserIdentifier = array_keys(UserFixtures::USERS_DATA)[0];

        $this->getSession()->getDriver()->getClient()->request(
            'PATCH',
            $this->router->generate(
                'rest_users_update',
                ['uuid' => $this->updatedUserIdentifier]
            ),
            [],
            [],
            [],
            json_encode(static::USER_DATA_TO_UPDATE)
        );
    }

    /**
     * @Then this user has a new name
     */
    public function userHasANewName(): void
    {
        $query = <<<SQL
SELECT * FROM user WHERE id = :id
SQL;

        $parameters = ['id' => $this->updatedUserIdentifier];
        $types = ['id' => \PDO::PARAM_STR];

        $statement = $this->connection->executeQuery($query, $parameters, $types);
        $result = $statement->fetchAll();

        Assert::count($result, 1);
        $queriedUser = $result[0];
        Assert::uuid($queriedUser['id']);
        Assert::same($queriedUser['username'], UserFixtures::USERS_DATA[$this->updatedUserIdentifier]['username']);
        Assert::same($queriedUser['first_name'], static::USER_DATA_TO_UPDATE['firstName']);
        Assert::same($queriedUser['last_name'], static::USER_DATA_TO_UPDATE['lastName']);
    }
}
