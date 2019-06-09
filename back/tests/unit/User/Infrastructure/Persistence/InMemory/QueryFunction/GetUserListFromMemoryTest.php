<?php

declare(strict_types=1);

/*
 * This file is part of AppSkeleton.
 *
 * Copyright (c) 2019 Damien Carcel <damien.carcel@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Carcel\Tests\Unit\User\Infrastructure\Persistence\InMemory\QueryFunction;

use Carcel\Tests\Fixtures\UserFixtures;
use Carcel\User\Domain\Model\Read\UserList;
use Carcel\User\Domain\Repository\UserRepositoryInterface;
use Carcel\User\Infrastructure\Persistence\InMemory\QueryFunction\GetUserListFromMemory;
use Carcel\User\Infrastructure\Persistence\InMemory\Repository\UserRepository;
use PHPUnit\Framework\TestCase;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
class GetUserListFromMemoryTest extends TestCase
{
    /** @var GetUserListFromMemory */
    private $getUserListFromMemory;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->getUserListFromMemory = new GetUserListFromMemory($this->instantiateInMemoryUserRepository());
    }

    /** @test */
    public function itIsAGetUserListQuery(): void
    {
        $this->assertInstanceOf(GetUserListFromMemory::class, $this->getUserListFromMemory);
    }

    /** @test */
    public function itGetsAListOfUsers(): void
    {
        $users = ($this->getUserListFromMemory)(10, 1);

        $this->assertInstanceOf(UserList::class, $users);
        $this->assertFollowingUserListShouldBeRetrieved($users, [
            '02432f0b-c33e-4d71-8ba9-a5e3267a45d5',
            '08acf31d-2e62-44e9-ba18-fd160ac125ad',
            '1605a575-77e5-4427-bbdb-2ebcb8cc8033',
            '22cd05c9-622d-4dcb-8837-1975e8c08812',
            '2a2a63c2-f01a-4b28-b52b-922bd6a170f5',
            '3553b4cf-49ab-4dd6-ba6e-e09b5b96115c',
            '5eefa64f-0800-4fe2-b86f-f3d96bf7d602',
            '7f57d041-a612-4a5a-a61a-e0c96b2c576e',
            '9f9e9cd2-88bb-438f-b825-b9610c6ee3f4',
            'd24b8b4a-2476-48f7-b865-ee5318d845f3',
        ]);
    }

    /** @test */
    public function itGetsALimitedListOfUsers(): void
    {
        $users = ($this->getUserListFromMemory)(2, 1);

        $this->assertInstanceOf(UserList::class, $users);
        $this->assertFollowingUserListShouldBeRetrieved($users, [
            '02432f0b-c33e-4d71-8ba9-a5e3267a45d5',
            '08acf31d-2e62-44e9-ba18-fd160ac125ad',
        ]);
    }

    /** @test */
    public function itGetsAListOfOneUserStartingACertainPage(): void
    {
        $users = ($this->getUserListFromMemory)(1, 2);

        $this->assertInstanceOf(UserList::class, $users);
        $this->assertFollowingUserListShouldBeRetrieved($users, [
            '08acf31d-2e62-44e9-ba18-fd160ac125ad',
        ]);
    }

    /** @test */
    public function itGetsAListOfUsersStartingACertainPage(): void
    {
        $users = ($this->getUserListFromMemory)(5, 2);

        $this->assertInstanceOf(UserList::class, $users);
        $this->assertFollowingUserListShouldBeRetrieved($users, [
            '3553b4cf-49ab-4dd6-ba6e-e09b5b96115c',
            '5eefa64f-0800-4fe2-b86f-f3d96bf7d602',
            '7f57d041-a612-4a5a-a61a-e0c96b2c576e',
            '9f9e9cd2-88bb-438f-b825-b9610c6ee3f4',
            'd24b8b4a-2476-48f7-b865-ee5318d845f3',
        ]);
    }

    /** @test */
    public function itGetsAnEmptyListOfUsersIfThePageIsTooHigh(): void
    {
        $users = ($this->getUserListFromMemory)(10, 3);

        $this->assertInstanceOf(UserList::class, $users);
        $this->assertFollowingUserListShouldBeRetrieved($users, []);
    }

    /**
     * @param UserList $users
     * @param array    $usersIds
     */
    private function assertFollowingUserListShouldBeRetrieved(UserList $users, array $usersIds): void
    {
        $normalizedExpectedUsers = [];
        foreach ($usersIds as $id) {
            $normalizedExpectedUsers[] = UserFixtures::getNormalizedUser($id);
        }

        $this->assertSame($users->normalize(), $normalizedExpectedUsers);
    }

    /**
     * @return UserRepositoryInterface
     */
    private function instantiateInMemoryUserRepository(): UserRepositoryInterface
    {
        $repository = new UserRepository();

        $users = UserFixtures::instantiateUserEntities();
        foreach ($users as $user) {
            $repository->save($user);
        }

        return $repository;
    }
}
