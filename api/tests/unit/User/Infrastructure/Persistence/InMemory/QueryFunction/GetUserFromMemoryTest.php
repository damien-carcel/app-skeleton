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

namespace Carcel\Tests\Unit\User\Infrastructure\Persistence\InMemory\QueryFunction;

use Carcel\Tests\Fixtures\UserFixtures;
use Carcel\User\Domain\Factory\UserFactory;
use Carcel\User\Domain\Model\Read\User;
use Carcel\User\Domain\Repository\UserRepositoryInterface;
use Carcel\User\Infrastructure\Persistence\InMemory\QueryFunction\GetUserFromMemory;
use Carcel\User\Infrastructure\Persistence\InMemory\Repository\UserRepository;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class GetUserFromMemoryTest extends TestCase
{
    private const TONY_STARK_ID = '02432f0b-c33e-4d71-8ba9-a5e3267a45d5';

    private GetUserFromMemory $getUserFromMemory;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->getUserFromMemory = new GetUserFromMemory($this->userRepository());
    }

    /** @test */
    public function itGetsAUserById(): void
    {
        $user = $this->getUserFromMemory->byId(Uuid::fromString(static::TONY_STARK_ID));

        static::assertUserShouldBeRetrieved($user, static::TONY_STARK_ID);
    }

    /** @test */
    public function itGetsAUserByEmail(): void
    {
        $user = $this->getUserFromMemory->byEmail(
            UserFixtures::USERS_DATA[static::TONY_STARK_ID]['email']
        );

        static::assertUserShouldBeRetrieved($user, static::TONY_STARK_ID);
    }

    /** @test */
    public function itDoesntGetByEmailAUserThatDoesNotExist(): void
    {
        $user = $this->getUserFromMemory->byId(Uuid::fromString(UserFixtures::ID_OF_NON_EXISTENT_USER));

        static::assertNull($user);
    }

    /** @test */
    public function itDoesntGetByIdAUserThatDoesNotExist(): void
    {
        $user = $this->getUserFromMemory->byEmail('fake.email@whatever.com');

        static::assertNull($user);
    }

    private function assertUserShouldBeRetrieved(User $user, string $usersId): void
    {
        static::assertSame(UserFixtures::getNormalizedUser($usersId)['id'], $user->getId());
        static::assertSame(UserFixtures::getNormalizedUser($usersId)['email'], $user->getEmail());
        static::assertSame(UserFixtures::getNormalizedUser($usersId)['firstName'], $user->getFirstName());
        static::assertSame(UserFixtures::getNormalizedUser($usersId)['lastName'], $user->getLastName());
    }

    private function userRepository(): UserRepositoryInterface
    {
        $factory = new UserFactory();
        $repository = new UserRepository();

        $userIds = array_keys(UserFixtures::USERS_DATA);

        foreach ($userIds as $id) {
            $user = $factory->create(
                $id,
                UserFixtures::USERS_DATA[$id]['firstName'],
                UserFixtures::USERS_DATA[$id]['lastName'],
                UserFixtures::USERS_DATA[$id]['email'],
            );

            $repository->create($user);
        }

        return $repository;
    }
}
