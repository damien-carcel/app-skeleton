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
        $userList = $this->getUserListFromMemory->execute(10, 1);

        $this->assertInstanceOf(UserList::class, $userList);
        $this->assertSame(
            array_slice(UserFixtures::getNormalizedUsers(), 0, 10),
            $userList->normalize()
        );
    }

    /** @test */
    public function itGetsALimitedListOfUsers(): void
    {
        $userList = $this->getUserListFromMemory->execute(2, 1);

        $this->assertInstanceOf(UserList::class, $userList);
        $this->assertSame(
            array_slice(UserFixtures::getNormalizedUsers(), 0, 2),
            $userList->normalize()
        );
    }

    /** @test */
    public function itGetsAListOfOneUserStartingACertainPage(): void
    {
        $userList = $this->getUserListFromMemory->execute(1, 2);

        $this->assertInstanceOf(UserList::class, $userList);
        $this->assertSame(
            array_slice(UserFixtures::getNormalizedUsers(), 1, 2),
            $userList->normalize()
        );
    }

    /** @test */
    public function itGetsAListOfUsersStartingACertainPage(): void
    {
        $userList = $this->getUserListFromMemory->execute(5, 2);

        $this->assertInstanceOf(UserList::class, $userList);
        $this->assertSame(
            array_slice(UserFixtures::getNormalizedUsers(), 5, 10),
            $userList->normalize()
        );
    }

    /** @test */
    public function itGetsAnEmptyListOfUsersIfThePageIsTooHigh(): void
    {
        $userList = $this->getUserListFromMemory->execute(10, 3);

        $this->assertInstanceOf(UserList::class, $userList);
        $this->assertSame(
            [],
            $userList->normalize()
        );
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
