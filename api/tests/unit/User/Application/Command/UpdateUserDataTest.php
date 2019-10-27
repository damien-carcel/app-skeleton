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

namespace Carcel\Tests\Unit\User\Application\Command;

use Carcel\User\Application\Command\UpdateUserData;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class UpdateUserDataTest extends TestCase
{
    /** @test */
    public function itIsAChangeUserNameCommand(): void
    {
        $createUser = $this->instantiateValidChangeUserName();

        static::assertInstanceOf(UpdateUserData::class, $createUser);
    }

    /** @test */
    public function itReturnsTheUsersId(): void
    {
        $createUser = $this->instantiateValidChangeUserName();

        static::assertEquals(Uuid::fromString('3d8fbf56-3a34-465b-9776-c3b69c510eef'), $createUser->identifier());
    }

    /** @test */
    public function itReturnsTheUsersRmail(): void
    {
        $createUser = $this->instantiateValidChangeUserName();

        static::assertSame('batman@justiceleague.org', $createUser->email());
    }

    /** @test */
    public function itReturnsTheUsersFirstName(): void
    {
        $createUser = $this->instantiateValidChangeUserName();

        static::assertSame('Bruce', $createUser->firstName());
    }

    /** @test */
    public function itReturnsTheUsersLastName(): void
    {
        $createUser = $this->instantiateValidChangeUserName();

        static::assertSame('Wayne', $createUser->lastName());
    }

    private function instantiateValidChangeUserName(): UpdateUserData
    {
        return new UpdateUserData(
            Uuid::fromString('3d8fbf56-3a34-465b-9776-c3b69c510eef'),
            'batman@justiceleague.org',
            'Bruce',
            'Wayne'
        );
    }
}
