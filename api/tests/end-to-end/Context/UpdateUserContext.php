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
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\Routing\RouterInterface;
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
     * @When I change the data of an existing user
     */
    public function changeTheNameOfAnExistingUser(): void
    {
        $this->updatedUserIdentifier = array_keys(UserFixtures::USERS_DATA)[0];

        $this->client->request(
            'PUT',
            $this->router->generate(
                'api_users_update',
                ['id' => $this->updatedUserIdentifier]
            ),
            [],
            [],
            [],
            json_encode(self::USER_DATA_TO_UPDATE),
        );
    }

    /**
     * @When I try to change the data of an existing user with invalid ones
     */
    public function updateUserWithInvalidData(): void
    {
        $this->client->request(
            'PUT',
            $this->router->generate(
                'api_users_update',
                ['id' => array_keys(UserFixtures::USERS_DATA)[0]]
            ),
            [],
            [],
            [],
            json_encode([
                'firstName' => '',
                'lastName' => '',
                'email' => 'not an email',
            ]),
        );
    }

    /**
     * @When I try to change the name of a user that does not exist
     */
    public function changeTheNameOfAUserThatDoesNotExist(): void
    {
        $this->client->request(
            'PUT',
            $this->router->generate(
                'api_users_update',
                ['id' => UserFixtures::ID_OF_NON_EXISTENT_USER]
            ),
            [],
            [],
            [],
            json_encode(self::USER_DATA_TO_UPDATE),
        );
    }

    /**
     * @Then this user has new email, first name and last name
     */
    public function userHasNewData(): void
    {
        Assert::same($this->client->getResponse()->getStatusCode(), 202);

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
        Assert::same($queriedUser['email'], self::USER_DATA_TO_UPDATE['email']);
        Assert::same($queriedUser['first_name'], self::USER_DATA_TO_UPDATE['firstName']);
        Assert::same($queriedUser['last_name'], self::USER_DATA_TO_UPDATE['lastName']);
    }

    /**
     * @Then I cannot change the user data
     */
    public function iCannotChangeTheUserData(): void
    {
        Assert::same($this->client->getResponse()->getStatusCode(), 400);
        Assert::contains(
            $this->client->getResponse()->getContent(),
            'This value should not be blank.'
        );
        Assert::contains(
            $this->client->getResponse()->getContent(),
            'This value is not a valid email address.'
        );
    }

    /**
     * @Then  I got nothing to update
     */
    public function gotNothingToUpdate(): void
    {
        Assert::same($this->client->getResponse()->getStatusCode(), 404);
        Assert::contains(
            $this->client->getResponse()->getContent(),
            sprintf('There is no user with identifier \u0022%s\u0022', UserFixtures::ID_OF_NON_EXISTENT_USER),
        );
    }
}
