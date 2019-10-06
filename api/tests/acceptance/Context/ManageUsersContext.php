<?php

declare(strict_types=1);

/*
 * This file is part of app-skeleton.
 *
 * Copyright (c) 2017 Damien Carcel <damien.carcel@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Carcel\Tests\Acceptance\Context;

use Behat\Behat\Context\Context;
use Carcel\Tests\Fixtures\UserFixtures;
use Carcel\User\Application\Query\GetUser;
use Carcel\User\Application\Query\GetUserHandler;
use Carcel\User\Application\Query\GetUserList as GetUserListQuery;
use Carcel\User\Application\Query\GetUserListHandler;
use Carcel\User\Domain\Model\Read\UserList;
use Carcel\User\Domain\Model\Write\User;
use Ramsey\Uuid\Uuid;
use Webmozart\Assert\Assert;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class ManageUsersContext implements Context
{
    private $getUserListHandler;
    private $getUserHandler;

    /** @var UserList */
    private $userList;

    /** @var User */
    private $user;

    public function __construct(
        GetUserListHandler $getUserListHandler,
        GetUserHandler $getUserHandler
    ) {
        $this->getUserListHandler = $getUserListHandler;
        $this->getUserHandler = $getUserHandler;
    }

    /**
     * @When I ask for the :position page of :quantity users
     */
    public function listUsers(string $position, int $quantity): void
    {
        $pageNumber = (int) substr($position, 0, 1);

        $this->userList = ($this->getUserListHandler)(new GetUserListQuery($quantity, $pageNumber));
    }

    /**
     * @When I ask for a specific user
     */
    public function askForASpecificUser(): void
    {
        $uuidList = array_keys(UserFixtures::USERS_DATA);
        $uuid = Uuid::fromString($uuidList[0]);

        $this->user = ($this->getUserHandler)(new GetUser($uuid));
    }

    /**
     * @Then the :position :quantity users should be retrieved
     */
    public function allUsersShouldBeRetrieved(string $position, int $quantity): void
    {
        $pageNumber = (int) substr($position, 0, 1);

        Assert::same($this->userList->normalize(), array_slice(
            UserFixtures::getNormalizedUsers(),
            ($pageNumber - 1) * $quantity,
            $quantity
        ));
    }

    /**
     * @Then the specified user should be retrieved
     */
    public function specifiedUserShouldBeRetrieved(): void
    {
        $uuidList = array_keys(UserFixtures::USERS_DATA);

        Assert::same(
            UserFixtures::getNormalizedUser($uuidList[0]),
            [
                'id' => $this->user->id()->toString(),
                'username' => $this->user->getUsername(),
                'firstName' => $this->user->getFirstName(),
                'lastName' => $this->user->getLastName(),
            ]
        );
    }
}
