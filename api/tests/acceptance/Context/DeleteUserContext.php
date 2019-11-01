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
use Carcel\User\Application\Command\DeleteUser;
use Carcel\User\Application\Command\DeleteUserHandler;
use Carcel\User\Domain\Exception\UserDoesNotExist;
use Carcel\User\Domain\Repository\UserRepositoryInterface;
use Ramsey\Uuid\Uuid;
use Webmozart\Assert\Assert;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class DeleteUserContext implements Context
{
    /** @var string */
    private $deletedUserIdentifier;

    /** @var UserDoesNotExist */
    private $caughtException;

    private $deleteUserHandler;
    private $userRepository;

    public function __construct(DeleteUserHandler $deleteUserHandler, UserRepositoryInterface $userRepository)
    {
        $this->deleteUserHandler = $deleteUserHandler;
        $this->userRepository = $userRepository;
    }

    /**
     * @When I delete a user
     */
    public function askForASpecificUser(): void
    {
        $this->deletedUserIdentifier = array_keys(UserFixtures::USERS_DATA)[0];

        $deleteUser = new DeleteUser(Uuid::fromString($this->deletedUserIdentifier));
        ($this->deleteUserHandler)($deleteUser);
    }

    /**
     * @When I try to delete a user that does not exist
     */
    public function deleteAUserThatDoesNotExist(): void
    {
        try {
            ($this->deleteUserHandler)(new DeleteUser(Uuid::fromString(UserFixtures::ID_OF_NON_EXISTENT_USER)));
        } catch (\Exception $exception) {
            $this->caughtException = $exception;
        }
    }

    /**
     * @Then the user is deleted
     */
    public function specifiedUserShouldBeRetrieved(): void
    {
        Assert::null($this->userRepository->find($this->deletedUserIdentifier));
    }

    /**
     * @Then I got nothing to delete
     */
    public function gotNothingToDelete(): void
    {
        Assert::isInstanceOf($this->caughtException, UserDoesNotExist::class);
    }
}