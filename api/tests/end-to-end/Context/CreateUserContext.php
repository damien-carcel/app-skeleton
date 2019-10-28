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
use Doctrine\DBAL\Driver\Connection;
use Symfony\Component\Routing\RouterInterface;
use Webmozart\Assert\Assert;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class CreateUserContext extends RawMinkContext
{
    private const NEW_USER = [
        'email' => 'batman@justiceligue.org',
        'firstName' => 'Bruce',
        'lastName' => 'Wayne',
    ];

    private $router;
    private $connection;

    public function __construct(RouterInterface $router, Connection $connection)
    {
        $this->router = $router;
        $this->connection = $connection;
    }

    /**
     * @When I create a new user
     */
    public function createNewUser(): void
    {
        $this->getSession()->getDriver()->getClient()->request(
            'POST',
            $this->router->generate('rest_users_create'),
            [],
            [],
            [],
            json_encode(static::NEW_USER)
        );
    }

    /**
     * @Then a new user is created
     */
    public function newUserIsCreated(): void
    {
        $query = <<<SQL
SELECT * FROM user WHERE email = :email
SQL;

        $parameters = ['email' => static::NEW_USER['email']];
        $types = ['email' => \PDO::PARAM_STR];

        $statement = $this->connection->executeQuery($query, $parameters, $types);
        $result = $statement->fetchAll();

        Assert::count($result, 1);

        $queriedUser = $result[0];
        Assert::uuid($queriedUser['id']);
        Assert::same($queriedUser['email'], static::NEW_USER['email']);
        Assert::same($queriedUser['first_name'], static::NEW_USER['firstName']);
        Assert::same($queriedUser['last_name'], static::NEW_USER['lastName']);
    }
}
