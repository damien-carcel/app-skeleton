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
    ];

    private $bus;
    private $userRepository;

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
        $createUser = new CreateUser(
            static::NEW_USER['email'],
            static::NEW_USER['firstName'],
            static::NEW_USER['lastName']
        );

        $this->bus->dispatch($createUser);
    }

    /**
     * @Then a new user is created
     */
    public function newUserIsCreated(): void
    {
        $users = $this->userRepository->findAll();
        Assert::count($users, 12);

        $fetchedUsersUuidList = array_map(function (User $user) {
            return (string) $user->id();
        }, $users);
        $fixtureUsersUuidList = array_keys(UserFixtures::USERS_DATA);

        $newUuidList = array_diff($fetchedUsersUuidList, $fixtureUsersUuidList);
        Assert::count($newUuidList, 1);

        $newUser = $this->userRepository->find(array_shift($newUuidList));
        Assert::same((string) $newUser->email(), static::NEW_USER['email']);
        Assert::same((string) $newUser->firstName(), static::NEW_USER['firstName']);
        Assert::same((string) $newUser->lastName(), static::NEW_USER['lastName']);
    }
}
