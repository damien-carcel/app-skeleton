<?php

declare(strict_types=1);

/*
 * This file is part of AppSkeleton.
 *
 * Copyright (c) 2017 Damien Carcel <damien.carcel@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Integration\Context;

use App\Domain\Model\User;
use App\Domain\Repository\UserRepositoryInterface;
use App\Tests\Fixtures\UserFixtures;
use Behat\Behat\Context\Context;
use Ramsey\Uuid\Uuid;
use Webmozart\Assert\Assert;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
class UserRepositoryContext implements Context
{
    /** @var UserRepositoryInterface */
    private $userRepository;

    /** @var User[] */
    private $results;

    /** @var User */
    private $result;

    /**
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param string $methodName
     *
     * @When the method :methodName from the Doctrine UserRepository is called
     */
    public function callMethodFromRepository(string $methodName): void
    {
        $this->results = $this->userRepository->$methodName();
    }

    /**
     * @param string $methodName
     * @param string $argument
     *
     * @When the method :methodName from the Doctrine UserRepository is called with argument :argument
     */
    public function callMethodFromRepositoryWithArgument(string $methodName, string $argument): void
    {
        $this->result = $this->userRepository->$methodName($argument);
    }

    /**
     * @param string $userId
     *
     * @When the user :userId is saved
     */
    public function saveUser(string $userId): void
    {
        $user = new User(
            Uuid::fromString($userId),
            UserFixtures::NORMALIZED_USERS[$userId]['username'],
            UserFixtures::NORMALIZED_USERS[$userId]['firstName'],
            UserFixtures::NORMALIZED_USERS[$userId]['lastName'],
            UserFixtures::NORMALIZED_USERS[$userId]['password'],
            UserFixtures::NORMALIZED_USERS[$userId]['salt'],
            UserFixtures::NORMALIZED_USERS[$userId]['roles']
        );

        $this->userRepository->save($user);
    }

    /**
     * @param string $userId
     *
     * @When the user :userId is removed
     */
    public function removeUser(string $userId): void
    {
        $user = $this->userRepository->find($userId);

        $this->userRepository->delete($user);
    }

    /**
     * @Then all the users should be retrieved from database
     */
    public function allUsersAreRetrieved(): void
    {
        Assert::count($this->results, 3);

        $normalizedUsers = [];
        foreach ($this->results as $user) {
            Assert::isInstanceOf($user, User::class);
            Assert::isInstanceOf($user->id(), Uuid::class);

            $normalizedUsers[(string) $user->id()] = [
                'firstName' => $user->getFirstName(),
                'lastName' => $user->getLastName(),
                'username' => $user->getUsername(),
                'password' => $user->getPassword(),
                'salt' => $user->getSalt(),
                'roles' => $user->getRoles(),
            ];
        }

        Assert::same($normalizedUsers, UserFixtures::NORMALIZED_USERS);
    }

    /**
     * @param string $userId
     *
     * @Then the user with ID :userId should be retrieved from database
     */
    public function userIsRetrieved(string $userId): void
    {
        Assert::isInstanceOf($this->result, User::class);
        Assert::isInstanceOf($this->result->id(), Uuid::class);

        $normalizedUser = [
            'firstName' => $this->result->getFirstName(),
            'lastName' => $this->result->getLastName(),
            'username' => $this->result->getUsername(),
            'password' => $this->result->getPassword(),
            'salt' => $this->result->getSalt(),
            'roles' => $this->result->getRoles(),
        ];

        Assert::same($normalizedUser, UserFixtures::NORMALIZED_USERS[$userId]);
    }

    /**
     * @param int $numberOfUsers
     *
     * @Then there should be :numberOfUsers user/users in database
     */
    public function shouldBeSuchNumberOfUsersInDatabase(int $numberOfUsers): void
    {
        Assert::count($this->userRepository->findAll(), $numberOfUsers);
    }
}
