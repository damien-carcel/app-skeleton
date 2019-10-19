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
use Carcel\User\Domain\Exception\UserDoesNotExist;
use Carcel\User\Domain\Model\Read\User;
use Ramsey\Uuid\Uuid;
use Webmozart\Assert\Assert;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class GetUserContext implements Context
{
    /** @var User */
    private $user;

    /** @var UserDoesNotExist */
    private $caughtException;

    private $getUserHandler;

    public function __construct(GetUserHandler $getUserHandler)
    {
        $this->getUserHandler = $getUserHandler;
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
     * @When I ask for a user that does not exist
     */
    public function askForAUserThatDoesNotExist(): void
    {
        $uuid = Uuid::fromString(UserFixtures::ID_OF_NON_EXISTENT_USER);

        try {
            $this->user = ($this->getUserHandler)(new GetUser($uuid));
        } catch (\Exception $exception) {
            $this->caughtException = $exception;
        }
    }

    /**
     * @Then the specified user should be retrieved
     */
    public function specifiedUserShouldBeRetrieved(): void
    {
        $uuidList = array_keys(UserFixtures::USERS_DATA);

        Assert::same(
            UserFixtures::getNormalizedUser($uuidList[0]),
            $this->user->normalize()
        );
    }

    /**
     * @Then I got no user
     */
    public function gotNoUser(): void
    {
        Assert::isInstanceOf($this->caughtException, UserDoesNotExist::class);
    }
}
