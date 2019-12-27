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
        'email' => 'new.ironman@avengers.org',
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
     * @When I change the data of an existing user
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
     * @When I try to change the data of an existing user with invalid ones
     */
    public function updateUserWithInvalidData(): void
    {
        $this->getSession()->getDriver()->getClient()->request(
            'PATCH',
            $this->router->generate(
                'rest_users_update',
                ['uuid' => array_keys(UserFixtures::USERS_DATA)[0]]
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
        $this->getSession()->getDriver()->getClient()->request(
            'PATCH',
            $this->router->generate(
                'rest_users_update',
                ['uuid' => UserFixtures::ID_OF_NON_EXISTENT_USER]
            ),
            [],
            [],
            [],
            json_encode(static::USER_DATA_TO_UPDATE),
        );
    }

    /**
     * @Then this user has new email, first name and last name
     */
    public function userHasNewData(): void
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
        Assert::same($queriedUser['email'], static::USER_DATA_TO_UPDATE['email']);
        Assert::same($queriedUser['first_name'], static::USER_DATA_TO_UPDATE['firstName']);
        Assert::same($queriedUser['last_name'], static::USER_DATA_TO_UPDATE['lastName']);
    }

    /**
     * @Then I cannot change the user data
     */
    public function iCannotChangeTheUserData(): void
    {
        $session = $this->getSession();

        Assert::same($session->getStatusCode(), 422);
        Assert::contains(
            $session->getPage()->getContent(),
            'This value should not be blank.'
        );
        Assert::contains(
            $session->getPage()->getContent(),
            'This value is not a valid email address.'
        );
    }

    /**
     * @Then  I got nothing to update
     */
    public function gotNothingToUpdate(): void
    {
        $session = $this->getSession();

        Assert::same($session->getStatusCode(), 404);
        Assert::contains(
            $session->getPage()->getContent(),
            sprintf('There is no user with identifier "%s"', UserFixtures::ID_OF_NON_EXISTENT_USER),
        );
    }
}
