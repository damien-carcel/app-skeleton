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

namespace Carcel\Tests\Unit\User\Domain\Model\Read;

use Carcel\Tests\Fixtures\UserFixtures;
use Carcel\User\Domain\Model\Read\UserList;
use PHPUnit\Framework\TestCase;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class UserListTest extends TestCase
{
    private $usersData;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->usersData = UserFixtures::getNormalizedUsers();
    }

    /** @test */
    public function itCanCreateAnEmptyUserList(): void
    {
        static::assertInstanceOf(UserList::class, new UserList([]));
    }

    /** @test */
    public function aUserListCanNormalizeItself(): void
    {
        $userList = $this->instantiateUserList();

        static::assertSame($this->usersData, $userList->normalize());
    }

    private function instantiateUserList(): UserList
    {
        return new UserList($this->usersData);
    }
}
