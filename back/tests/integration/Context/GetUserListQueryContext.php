<?php

declare(strict_types=1);

/*
 * This file is part of AppSkeleton.
 *
 * Copyright (c) 2018 Damien Carcel <damien.carcel@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Carcel\Tests\Integration\Context;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Carcel\Tests\Fixtures\UserFixtures;
use Carcel\User\Domain\Model\Read\UserList;
use Carcel\User\Domain\QueryFunction\GetUserList;
use Webmozart\Assert\Assert;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
class GetUserListQueryContext implements Context
{
    /** @var GetUserList */
    private $getUserListQuery;

    /** @var UserList */
    private $userList;

    /**
     * @param GetUserList $getUserListQuery
     */
    public function __construct(GetUserList $getUserListQuery)
    {
        $this->getUserListQuery = $getUserListQuery;
    }

    /**
     * @param int $quantity
     * @param int $pageNumber
     *
     * @When :quantity users are queried starting page :pageNumber
     * @When :quantity user is queried starting page :pageNumber
     */
    public function queryUsers(int $quantity, int $pageNumber): void
    {
        $this->userList = $this->getUserListQuery->execute($quantity, $pageNumber);
    }

    /**
     * @param TableNode $usersData
     *
     * @Then the following user list should be retrieved:
     */
    public function followingUserListShouldBeRetrieved(TableNode $usersData): void
    {
        $normalizedRetrievedUsers = $this->userList->normalize();
        $normalizedExpectedUsers = [];
        foreach ($usersData as $userData) {
            $normalizedExpectedUsers[] = UserFixtures::getNormalizedUser($userData['user_id']);
        }

        Assert::same($normalizedRetrievedUsers, $normalizedExpectedUsers);
    }

    /**
     * @Then the retrieved user list should be empty
     */
    public function retrievedUserListShouldBeEmpty(): void
    {
        $normalizedRetrievedUsers = $this->userList->normalize();

        Assert::isEmpty($normalizedRetrievedUsers);
    }
}
