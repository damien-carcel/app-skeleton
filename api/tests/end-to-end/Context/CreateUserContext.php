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

use Behat\Behat\Context\Context;
use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\Routing\RouterInterface;
use Webmozart\Assert\Assert;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class CreateUserContext implements Context
{
    private const NEW_VALID_USER = [
        'email' => 'batman@justiceligue.org',
        'firstName' => 'Bruce',
        'lastName' => 'Wayne',
        'password' => 'catwoman',
    ];

    private const NEW_INVALID_USER = [
        'email' => 'not an email',
        'firstName' => '',
        'lastName' => '',
        'password' => '',
    ];

    private Connection $connection;
    private KernelBrowser $client;
    private RouterInterface $router;

    public function __construct(Connection $connection, KernelBrowser $client, RouterInterface $router)
    {
        $this->connection = $connection;
        $this->client = $client;
        $this->router = $router;
    }

    /**
     * @When I create a new user
     */
    public function createNewUser(): void
    {
        $this->client->request(
            'POST',
            $this->router->generate('api_users_create'),
            [],
            [],
            [],
            json_encode(self::NEW_VALID_USER),
        );
    }

    /**
     * @When I try to create a user with invalid data
     */
    public function tryToCreateUserWithInvalidData(): void
    {
        $this->client->request(
            'POST',
            $this->router->generate('api_users_create'),
            [],
            [],
            [],
            json_encode(self::NEW_INVALID_USER),
        );
    }

    /**
     * @Then a new user is created
     */
    public function newUserIsCreated(): void
    {
        Assert::same($this->client->getResponse()->getStatusCode(), 202);

        $query = <<<SQL
            SELECT * FROM user WHERE email = :email
            SQL;

        $parameters = ['email' => self::NEW_VALID_USER['email']];
        $types = ['email' => \PDO::PARAM_STR];

        $statement = $this->connection->executeQuery($query, $parameters, $types);
        $result = $statement->fetchAll();

        Assert::count($result, 1);

        $queriedUser = $result[0];
        Assert::uuid($queriedUser['id']);
        Assert::same($queriedUser['email'], self::NEW_VALID_USER['email']);
        Assert::same($queriedUser['first_name'], self::NEW_VALID_USER['firstName']);
        Assert::same($queriedUser['last_name'], self::NEW_VALID_USER['lastName']);
        Assert::notContains($queriedUser['password'], self::NEW_VALID_USER['password']);
        Assert::contains($queriedUser['password'], '$argon2id$v=19$m=65536,t=4,p=1$');
    }

    /**
     * @Then I cannot create this user
     */
    public function iCannotCreateAnInvalidUser(): void
    {
        $response = $this->client->getResponse();

        Assert::same($response->getStatusCode(), 400);
        Assert::contains($response->getContent(), 'This value should not be blank.');
        Assert::contains($response->getContent(), 'This value is not a valid email address.');
    }
}
