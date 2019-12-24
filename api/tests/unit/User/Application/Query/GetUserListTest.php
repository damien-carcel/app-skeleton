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

namespace Carcel\Tests\Unit\User\Application\Query;

use Carcel\User\Application\Query\GetUserList;
use PHPUnit\Framework\TestCase;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class GetUserListTest extends TestCase
{
    /** @test */
    public function itReturnsTheNumberOfUsersTheListWillContain(): void
    {
        $getUserList = $this->instantiateValidGetUserList();

        static::assertSame(10, $getUserList->numberOfUsers());
    }

    /** @test */
    public function itReturnsTheUserPage(): void
    {
        $getUserList = $this->instantiateValidGetUserList();

        static::assertSame(1, $getUserList->userPage());
    }

    private function instantiateValidGetUserList(): GetUserList
    {
        return new GetUserList(10, 1);
    }
}
