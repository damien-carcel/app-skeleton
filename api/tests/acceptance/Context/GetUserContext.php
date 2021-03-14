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
use Webmozart\Assert\Assert;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class GetUserContext implements Context
{
    private \Exception $caughtException;
    private User $user;

    private GetUserHandler $handler;

    public function __construct(GetUserHandler $handler)
    {
        $this->handler = $handler;
    }

    /**
     * @When I ask for a specific user
     */
    public function askForASpecificUser(): void
    {
        $getUser = new GetUser(array_keys(UserFixtures::USERS_DATA)[0]);

        $this->user = ($this->handler)($getUser);
    }

    /**
     * @When I ask for a user that does not exist
     */
    public function askForAUserThatDoesNotExist(): void
    {
        $getUser = new GetUser(UserFixtures::ID_OF_NON_EXISTENT_USER);

        try {
            ($this->handler)($getUser);
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
        $normalizedUser = $this->user->normalize();

        Assert::same($normalizedUser['id'], UserFixtures::getNormalizedUser($uuidList[0])['id']);
        Assert::same($normalizedUser['email'], UserFixtures::getNormalizedUser($uuidList[0])['email']);
        Assert::same($normalizedUser['firstName'], UserFixtures::getNormalizedUser($uuidList[0])['firstName']);
        Assert::same($normalizedUser['lastName'], UserFixtures::getNormalizedUser($uuidList[0])['lastName']);
    }

    /**
     * @Then I got no user
     */
    public function gotNoUser(): void
    {
        Assert::isInstanceOf($this->caughtException, UserDoesNotExist::class);
    }
}
