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

namespace Carcel\Tests\Unit\User\Application\Query;

use Carcel\User\Application\Query\GetUserList;
use PHPUnit\Framework\TestCase;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
class GetUserListTest extends TestCase
{
    /** @test */
    public function itIsAGetUserListQuery(): void
    {
        $getUserList = $this->instantiateValidGetUserList();

        $this->assertInstanceOf(GetUserList::class, $getUserList);
    }

    /** @test */
    public function itReturnsTheNumberOfUsersTheListWillContain(): void
    {
        $getUserList = $this->instantiateValidGetUserList();

        $this->assertSame(10, $getUserList->numberOfUsers());
    }

    /** @test */
    public function itReturnsTheUserPage(): void
    {
        $getUserList = $this->instantiateValidGetUserList();
        $this->assertSame(1, $getUserList->userPage());
    }

    /**
     * @return GetUserList
     */
    private function instantiateValidGetUserList(): GetUserList
    {
        return new GetUserList(10, 1);
    }
}
