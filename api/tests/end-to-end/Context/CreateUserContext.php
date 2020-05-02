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
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
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

    private ResponseInterface $response;

    private KernelInterface $kernel;
    private RouterInterface $router;
    private Connection $connection;

    public function __construct(KernelInterface $kernel, RouterInterface $router, Connection $connection)
    {
        $this->kernel = $kernel;
        $this->router = $router;
        $this->connection = $connection;
    }

    /**
     * @When I create a new user
     */
    public function createNewUser(): void
    {
        $this->response = $this->client()->request(
            'POST',
            $this->router->generate('api_create_users_post_collection'),
            [
                'json' => static::NEW_VALID_USER,
            ],
        );
    }

    /**
     * @When I try to create a user with invalid data
     */
    public function tryToCreateUserWithInvalidData(): void
    {
        $this->response = $this->client()->request(
            'POST',
            $this->router->generate('api_create_users_post_collection'),
            [
                'json' => static::NEW_INVALID_USER,
            ],
        );
    }

    /**
     * @Then a new user is created
     */
    public function newUserIsCreated(): void
    {
        Assert::same($this->response->getStatusCode(), 202);

        $query = <<<SQL
            SELECT * FROM user WHERE email = :email
            SQL;

        $parameters = ['email' => static::NEW_VALID_USER['email']];
        $types = ['email' => \PDO::PARAM_STR];

        $statement = $this->connection->executeQuery($query, $parameters, $types);
        $result = $statement->fetchAll();

        Assert::count($result, 1);

        $queriedUser = $result[0];
        Assert::uuid($queriedUser['id']);
        Assert::same($queriedUser['email'], static::NEW_VALID_USER['email']);
        Assert::same($queriedUser['first_name'], static::NEW_VALID_USER['firstName']);
        Assert::same($queriedUser['last_name'], static::NEW_VALID_USER['lastName']);
        Assert::same($queriedUser['password'], static::NEW_VALID_USER['password']);
    }

    /**
     * @Then I cannot create this invalid user
     */
    public function iCannotCreateAnInvalidUser(): void
    {
        Assert::same($this->response->getStatusCode(), 400);

        Assert::contains(
            $this->response->getContent(false),
            'This value should not be blank.'
        );
        Assert::contains(
            $this->response->getContent(false),
            'This value is not a valid email address.'
        );
    }

    private function client(): HttpClientInterface
    {
        return $this->kernel->getContainer()->get('test.api_platform.client');
    }
}
