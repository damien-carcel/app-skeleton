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
use Carcel\Tests\Fixtures\UserFixtures;
use Doctrine\DBAL\Connection;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Webmozart\Assert\Assert;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class UpdateUserContext implements Context
{
    private const USER_DATA_TO_UPDATE = [
        'email' => 'new.ironman@avengers.org',
        'firstName' => 'Peter',
        'lastName' => 'Parker',
    ];

    private string $updatedUserIdentifier;

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
     * @When I change the data of an existing user
     */
    public function changeTheNameOfAnExistingUser(): void
    {
        $this->updatedUserIdentifier = array_keys(UserFixtures::USERS_DATA)[0];

        $this->response = $this->client()->request(
            'PUT',
            $this->router->generate(
                'api_update_users_put_item',
                ['id' => $this->updatedUserIdentifier]
            ),
            [
                'json' => static::USER_DATA_TO_UPDATE,
            ],
        );
    }

    /**
     * @When I try to change the data of an existing user with invalid ones
     */
    public function updateUserWithInvalidData(): void
    {
        $this->response = $this->client()->request(
            'PUT',
            $this->router->generate(
                'api_update_users_put_item',
                ['id' => array_keys(UserFixtures::USERS_DATA)[0]]
            ),
            [
                'json' => [
                    'firstName' => '',
                    'lastName' => '',
                    'email' => 'not an email',
                ],
            ],
        );
    }

    /**
     * @When I try to change the name of a user that does not exist
     */
    public function changeTheNameOfAUserThatDoesNotExist(): void
    {
        $this->response = $this->client()->request(
            'PUT',
            $this->router->generate(
                'api_update_users_put_item',
                ['id' => UserFixtures::ID_OF_NON_EXISTENT_USER]
            ),
            [
                'json' => static::USER_DATA_TO_UPDATE,
            ],
        );
    }

    /**
     * @Then this user has new email, first name and last name
     */
    public function userHasNewData(): void
    {
        Assert::same($this->response->getStatusCode(), 202);

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
        Assert::same($queriedUser['email'], static::USER_DATA_TO_UPDATE['email']);
        Assert::same($queriedUser['first_name'], static::USER_DATA_TO_UPDATE['firstName']);
        Assert::same($queriedUser['last_name'], static::USER_DATA_TO_UPDATE['lastName']);
    }

    /**
     * @Then I cannot change the user data
     */
    public function iCannotChangeTheUserData(): void
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

    /**
     * @Then  I got nothing to update
     */
    public function gotNothingToUpdate(): void
    {
        Assert::same($this->response->getStatusCode(), 404);
        Assert::contains(
            $this->response->getContent(false),
            sprintf('There is no user with identifier \u0022%s\u0022', UserFixtures::ID_OF_NON_EXISTENT_USER),
        );
    }

    private function client(): HttpClientInterface
    {
        return $this->kernel->getContainer()->get('test.api_platform.client');
    }
}
