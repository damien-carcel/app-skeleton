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

namespace Carcel\Tests\Integration\User\Infrastructure\Persistence\Doctrine\QueryFunction;

use Carcel\Tests\Fixtures\UserFixtures;
use Carcel\Tests\Integration\TestCase;
use Carcel\User\Domain\Model\Read\UserList;
use Carcel\User\Domain\QueryFunction\GetUserList;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use PHPUnit\Framework\Assert;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class GetUserListFromDatabaseTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->prepareDatabaseWithUsers();
    }

    /** @test */
    public function itGetsAListOfUsers(): void
    {
        $users = $this->queryUsersStartingPage(10, 1);

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
        $users = $this->queryUsersStartingPage(2, 1);

        $this->assertFollowingUserListShouldBeRetrieved($users, [
            '02432f0b-c33e-4d71-8ba9-a5e3267a45d5',
            '08acf31d-2e62-44e9-ba18-fd160ac125ad',
        ]);
    }

    /** @test */
    public function itGetsAListOfOneUserStartingACertainPage(): void
    {
        $users = $this->queryUsersStartingPage(1, 2);

        $this->assertFollowingUserListShouldBeRetrieved($users, [
            '08acf31d-2e62-44e9-ba18-fd160ac125ad',
        ]);
    }

    /** @test */
    public function itGetsAListOfUsersStartingACertainPage(): void
    {
        $users = $this->queryUsersStartingPage(5, 2);

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
        $users = $this->queryUsersStartingPage(10, 3);

        $this->assertFollowingUserListShouldBeRetrieved($users, []);
    }

    /**
     * @param int $quantity
     * @param int $pageNumber
     *
     * @return UserList
     */
    private function queryUsersStartingPage(int $quantity, int $pageNumber): UserList
    {
        $queryHandler = $this->container()->get(GetUserList::class);

        return ($queryHandler)($quantity, $pageNumber);
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

        Assert::assertSame($users->normalize(), $normalizedExpectedUsers);
    }

    private function prepareDatabaseWithUsers(): void
    {
        $entityManager = $this->container()->get('doctrine.orm.entity_manager');

        $purger = new ORMPurger($entityManager);
        $executor = new ORMExecutor($entityManager, $purger);
        $executor->execute([new UserFixtures()]);
    }
}
