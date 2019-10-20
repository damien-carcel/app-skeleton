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

namespace Carcel\Tests\Acceptance\Context;

use Behat\Behat\Context\Context;
use Carcel\Tests\Fixtures\UserFixtures;
use Carcel\User\Application\Command\ChangeUserName;
use Carcel\User\Application\Command\ChangeUserNameHandler;
use Carcel\User\Domain\Exception\UserDoesNotExist;
use Carcel\User\Domain\Repository\UserRepositoryInterface;
use Ramsey\Uuid\Uuid;
use Webmozart\Assert\Assert;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class UpdateUserContext implements Context
{
    /** @var string */
    private $updatedUserIdentifier;

    /** @var UserDoesNotExist */
    private $caughtException;

    private $changeUserNameHandler;
    private $userRepository;

    public function __construct(ChangeUserNameHandler $changeUserNameHandler, UserRepositoryInterface $userRepository)
    {
        $this->changeUserNameHandler = $changeUserNameHandler;
        $this->userRepository = $userRepository;
    }

    /**
     * @When I change the name of an existing user
     */
    public function changeTheNameOfAnExistingUser(): void
    {
        $this->updatedUserIdentifier = array_keys(UserFixtures::USERS_DATA)[0];

        $changeUserName = new ChangeUserName(
            Uuid::fromString($this->updatedUserIdentifier),
            'Peter',
            'Parker'
        );
        ($this->changeUserNameHandler)($changeUserName);
    }

    /**
     * @When I change the first name of an existing user
     */
    public function changeTheFirstNameOfAnExistingUser(): void
    {
        $this->updatedUserIdentifier = array_keys(UserFixtures::USERS_DATA)[0];

        $changeUserName = new ChangeUserName(
            Uuid::fromString($this->updatedUserIdentifier),
            'Peter',
            UserFixtures::USERS_DATA[$this->updatedUserIdentifier]['lastName']
        );
        ($this->changeUserNameHandler)($changeUserName);
    }

    /**
     * @When I change the last name of an existing user
     */
    public function changeTheLastNameOfAnExistingUser(): void
    {
        $this->updatedUserIdentifier = array_keys(UserFixtures::USERS_DATA)[0];

        $changeUserName = new ChangeUserName(
            Uuid::fromString($this->updatedUserIdentifier),
            UserFixtures::USERS_DATA[$this->updatedUserIdentifier]['firstName'],
            'Parker'
        );
        ($this->changeUserNameHandler)($changeUserName);
    }

    /**
     * @When I try to change the name of a user that does not exist
     */
    public function changeTheNameOfAUserThatDoesNotExist(): void
    {
        try {
            $changeUserName = new ChangeUserName(
                Uuid::fromString(Uuid::fromString(UserFixtures::ID_OF_NON_EXISTENT_USER)),
                'Peter',
                'Parker'
            );
            ($this->changeUserNameHandler)($changeUserName);
        } catch (\Exception $exception) {
            $this->caughtException = $exception;
        }
    }

    /**
     * @Then this user has a new name
     */
    public function userHasANewName(): void
    {
        $updatedUser = $this->userRepository->find($this->updatedUserIdentifier);

        Assert::same($updatedUser->username(), UserFixtures::USERS_DATA[$this->updatedUserIdentifier]['username']);
        Assert::same($updatedUser->firstName(), 'Peter');
        Assert::same($updatedUser->lastName(), 'Parker');
    }

    /**
     * @Then this user has a new first name
     */
    public function userHasANewFirstName(): void
    {
        $updatedUser = $this->userRepository->find($this->updatedUserIdentifier);

        Assert::same($updatedUser->username(), UserFixtures::USERS_DATA[$this->updatedUserIdentifier]['username']);
        Assert::same($updatedUser->firstName(), 'Peter');
        Assert::same($updatedUser->lastName(), UserFixtures::USERS_DATA[$this->updatedUserIdentifier]['lastName']);
    }

    /**
     * @Then this user has a new last name
     */
    public function userHasANewLastName(): void
    {
        $updatedUser = $this->userRepository->find($this->updatedUserIdentifier);

        Assert::same($updatedUser->username(), UserFixtures::USERS_DATA[$this->updatedUserIdentifier]['username']);
        Assert::same($updatedUser->firstName(), UserFixtures::USERS_DATA[$this->updatedUserIdentifier]['firstName']);
        Assert::same($updatedUser->lastName(), 'Parker');
    }

    /**
     * @Then  I got nothing to update
     */
    public function gotNothingToUpdate(): void
    {
        Assert::isInstanceOf($this->caughtException, UserDoesNotExist::class);
    }
}
