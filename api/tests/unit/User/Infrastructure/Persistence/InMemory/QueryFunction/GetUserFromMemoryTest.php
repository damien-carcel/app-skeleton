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
use Carcel\User\Domain\Repository\UserRepository;
use Carcel\User\Infrastructure\Persistence\InMemory\QueryFunction\GetUserFromMemory;
use Carcel\User\Infrastructure\Persistence\InMemory\Repository\InMemoryUserRepository;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class GetUserFromMemoryTest extends TestCase
{
    private const TONY_STARK_ID = '02432f0b-c33e-4d71-8ba9-a5e3267a45d5';

    private GetUserFromMemory $getUser;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->getUser = new GetUserFromMemory($this->userRepository());
    }

    /** @test */
    public function itGetsAUserById(): void
    {
        $user = ($this->getUser)(Uuid::fromString(self::TONY_STARK_ID));

        self::assertUserShouldBeRetrieved($user, self::TONY_STARK_ID);
    }

    /** @test */
    public function itDoesntGetByIdAUserThatDoesNotExist(): void
    {
        $user = ($this->getUser)(Uuid::fromString(UserFixtures::ID_OF_NON_EXISTENT_USER));

        self::assertNull($user);
    }

    private function assertUserShouldBeRetrieved(User $user, string $usersId): void
    {
        $normalizedUser = $user->normalize();

        self::assertSame(UserFixtures::getNormalizedUser($usersId)['id'], $normalizedUser['id']);
        self::assertSame(UserFixtures::getNormalizedUser($usersId)['email'], $normalizedUser['email']);
        self::assertSame(UserFixtures::getNormalizedUser($usersId)['firstName'], $normalizedUser['firstName']);
        self::assertSame(UserFixtures::getNormalizedUser($usersId)['lastName'], $normalizedUser['lastName']);
    }

    private function userRepository(): UserRepository
    {
        $factory = new UserFactory();
        $repository = new InMemoryUserRepository();

        $userIds = array_keys(UserFixtures::USERS_DATA);

        foreach ($userIds as $id) {
            $user = $factory->create(
                $id,
                UserFixtures::USERS_DATA[$id]['firstName'],
                UserFixtures::USERS_DATA[$id]['lastName'],
                UserFixtures::USERS_DATA[$id]['email'],
                UserFixtures::USERS_DATA[$id]['password'],
            );

            $repository->create($user);
        }

        return $repository;
    }
}
