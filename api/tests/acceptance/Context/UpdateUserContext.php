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
use Carcel\User\Application\Command\UpdateUserData;
use Carcel\User\Application\Command\UpdateUserDataHandler;
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

    public function __construct(UpdateUserDataHandler $changeUserNameHandler, UserRepositoryInterface $userRepository)
    {
        $this->changeUserNameHandler = $changeUserNameHandler;
        $this->userRepository = $userRepository;
    }

    /**
     * @When I change the data of an existing user
     */
    public function changeTheNameOfAnExistingUser(): void
    {
        $this->updatedUserIdentifier = array_keys(UserFixtures::USERS_DATA)[0];

        $changeUserName = new UpdateUserData(
            Uuid::fromString($this->updatedUserIdentifier),
            'new.ironman@avengers.org',
            'Peter',
            'Parker'
        );
        ($this->changeUserNameHandler)($changeUserName);
    }

    /**
     * @When I change the email of an existing user
     */
    public function changeTheEmailOfAnExistingUser(): void
    {
        $this->updatedUserIdentifier = array_keys(UserFixtures::USERS_DATA)[0];

        $changeUserName = new UpdateUserData(
            Uuid::fromString($this->updatedUserIdentifier),
            'new.ironman@avengers.org',
            UserFixtures::USERS_DATA[$this->updatedUserIdentifier]['firstName'],
            UserFixtures::USERS_DATA[$this->updatedUserIdentifier]['lastName']
        );
        ($this->changeUserNameHandler)($changeUserName);
    }

    /**
     * @When I change the first name of an existing user
     */
    public function changeTheFirstNameOfAnExistingUser(): void
    {
        $this->updatedUserIdentifier = array_keys(UserFixtures::USERS_DATA)[0];

        $changeUserName = new UpdateUserData(
            Uuid::fromString($this->updatedUserIdentifier),
            UserFixtures::USERS_DATA[$this->updatedUserIdentifier]['email'],
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

        $changeUserName = new UpdateUserData(
            Uuid::fromString($this->updatedUserIdentifier),
            UserFixtures::USERS_DATA[$this->updatedUserIdentifier]['email'],
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
            $changeUserName = new UpdateUserData(
                Uuid::fromString(Uuid::fromString(UserFixtures::ID_OF_NON_EXISTENT_USER)),
                'peter.parker@avengers.org',
                'Peter',
                'Parker'
            );
            ($this->changeUserNameHandler)($changeUserName);
        } catch (\Exception $exception) {
            $this->caughtException = $exception;
        }
    }

    /**
     * @Then this user has new email, first name and last name
     */
    public function userHasNewData(): void
    {
        $updatedUser = $this->userRepository->find($this->updatedUserIdentifier);

        Assert::same((string) $updatedUser->email(), 'new.ironman@avengers.org');
        Assert::same((string) $updatedUser->firstName(), 'Peter');
        Assert::same((string) $updatedUser->lastName(), 'Parker');
    }

    /**
     * @Then this user has a new email
     */
    public function userHasNewEmail(): void
    {
        $updatedUser = $this->userRepository->find($this->updatedUserIdentifier);

        Assert::same(
            (string) $updatedUser->email(),
            'new.ironman@avengers.org'
        );
        Assert::same(
            (string) $updatedUser->firstName(),
            UserFixtures::USERS_DATA[$this->updatedUserIdentifier]['firstName']
        );
        Assert::same(
            (string) $updatedUser->lastName(),
            UserFixtures::USERS_DATA[$this->updatedUserIdentifier]['lastName']
        );
    }

    /**
     * @Then this user has a new first name
     */
    public function userHasANewFirstName(): void
    {
        $updatedUser = $this->userRepository->find($this->updatedUserIdentifier);

        Assert::same(
            (string) $updatedUser->email(),
            UserFixtures::USERS_DATA[$this->updatedUserIdentifier]['email']
        );
        Assert::same(
            (string) $updatedUser->firstName(),
            'Peter'
        );
        Assert::same(
            (string) $updatedUser->lastName(),
            UserFixtures::USERS_DATA[$this->updatedUserIdentifier]['lastName']
        );
    }

    /**
     * @Then this user has a new last name
     */
    public function userHasANewLastName(): void
    {
        $updatedUser = $this->userRepository->find($this->updatedUserIdentifier);

        Assert::same(
            (string) $updatedUser->email(),
            UserFixtures::USERS_DATA[$this->updatedUserIdentifier]['email']
        );
        Assert::same(
            (string) $updatedUser->firstName(),
            UserFixtures::USERS_DATA[$this->updatedUserIdentifier]['firstName']
        );
        Assert::same(
            (string) $updatedUser->lastName(),
            'Parker'
        );
    }

    /**
     * @Then  I got nothing to update
     */
    public function gotNothingToUpdate(): void
    {
        Assert::isInstanceOf($this->caughtException, UserDoesNotExist::class);
    }
}
