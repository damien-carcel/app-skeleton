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
final class DeleteUserContext extends RawMinkContext
{
    private $router;
    private $connection;

    private $deletedUserIdentifier;

    public function __construct(RouterInterface $router, Connection $connection)
    {
        $this->router = $router;
        $this->connection = $connection;
    }

    /**
     * @When I delete a user
     */
    public function askForASpecificUser(): void
    {
        $this->deletedUserIdentifier = array_keys(UserFixtures::USERS_DATA)[0];

        $this->getSession()->getDriver()->getClient()->request(
            'DELETE',
            $this->router->generate('rest_users_delete', [
                'uuid' => $this->deletedUserIdentifier,
            ])
        );
    }

    /**
     * @Then the user is deleted
     */
    public function specifiedUserShouldBeRetrieved(): void
    {
        $query = <<<SQL
            SELECT * FROM user
            SQL;

        $statement = $this->connection->executeQuery($query);
        $results = $statement->fetchAll();

        Assert::count($results, count(UserFixtures::USERS_DATA) - 1);

        Assert::eq($this->filterQueriedUserData($results), $this->expectedUserListAfterDeletion());
    }

    private function filterQueriedUserData(array $users): array
    {
        return array_map(function (array $queriedUser) {
            return [
                'id' => $queriedUser['id'],
                'email' => $queriedUser['email'],
                'firstName' => $queriedUser['first_name'],
                'lastName' => $queriedUser['last_name'],
            ];
        }, $users);
    }

    private function expectedUserListAfterDeletion(): array
    {
        $normalizedFixtures = UserFixtures::getNormalizedUsers();

        return array_values(array_filter($normalizedFixtures, function (array $user) {
            return $this->deletedUserIdentifier !== $user['id'];
        }));
    }
}
