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
use Carcel\User\Application\Command\CreateUser;
use Carcel\User\Domain\Model\Write\User;
use Carcel\User\Domain\Repository\UserRepositoryInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Webmozart\Assert\Assert;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class CreateUserContext implements Context
{
    private const NEW_USER = [
        'email' => 'batman@justiceligue.org',
        'firstName' => 'Bruce',
        'lastName' => 'Wayne',
        'password' => 'catwoman',
    ];

    private HandlerFailedException $caughtException;

    private MessageBusInterface $bus;
    private UserRepositoryInterface $userRepository;

    public function __construct(MessageBusInterface $bus, UserRepositoryInterface $userRepository)
    {
        $this->bus = $bus;
        $this->userRepository = $userRepository;
    }

    /**
     * @When I create a new user
     */
    public function createNewUser(): void
    {
        $createUser = new CreateUser();
        $createUser->firstName = static::NEW_USER['firstName'];
        $createUser->lastName = static::NEW_USER['lastName'];
        $createUser->email = static::NEW_USER['email'];
        $createUser->password = static::NEW_USER['password'];

        $this->bus->dispatch($createUser);
    }

    /**
     * @When I try to create a user with invalid data
     */
    public function tryToCreateUserWithInvalidData(): void
    {
        $createUser = new CreateUser();
        $createUser->firstName = '';
        $createUser->lastName = '';
        $createUser->email = 'not an email';
        $createUser->password = '';

        try {
            $this->bus->dispatch($createUser);
        } catch (HandlerFailedException $exception) {
            $this->caughtException = $exception;
        }
    }

    /**
     * @Then a new user is created
     */
    public function newUserIsCreated(): void
    {
        $users = $this->userRepository->findAll();
        Assert::count($users, 12);

        $fetchedUsersUuidList = array_map(function (User $user) {
            return $user->id()->toString();
        }, $users);
        $fixtureUsersUuidList = array_keys(UserFixtures::USERS_DATA);

        $newUuidList = array_diff($fetchedUsersUuidList, $fixtureUsersUuidList);
        Assert::count($newUuidList, 1);

        $newUser = $this->userRepository->find(Uuid::fromString(array_shift($newUuidList)));
        Assert::same((string) $newUser->email(), static::NEW_USER['email']);
        Assert::same((string) $newUser->firstName(), static::NEW_USER['firstName']);
        Assert::same((string) $newUser->lastName(), static::NEW_USER['lastName']);
        Assert::same((string) $newUser->password(), static::NEW_USER['password']);
    }

    /**
     * @Then I cannot create this invalid user
     */
    public function iCannotCreateAnInvalidUser(): void
    {
        $handledExceptions = $this->caughtException->getNestedExceptions();

        Assert::count($handledExceptions, 1);
        Assert::isInstanceOf(current($handledExceptions), \InvalidArgumentException::class);
    }
}
