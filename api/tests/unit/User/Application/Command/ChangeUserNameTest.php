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

use Carcel\User\Application\Command\ChangeUserName;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class ChangeUserNameTest extends TestCase
{
    /** @test */
    public function itIsAChangeUserNameCommand(): void
    {
        $createUser = $this->instantiateValidChangeUserName();

        $this->assertInstanceOf(ChangeUserName::class, $createUser);
    }

    /** @test */
    public function itReturnsTheUsersId(): void
    {
        $createUser = $this->instantiateValidChangeUserName();

        $this->assertEquals(Uuid::fromString('3d8fbf56-3a34-465b-9776-c3b69c510eef'), $createUser->identifier());
    }

    /** @test */
    public function itReturnsTheUsersFirstname(): void
    {
        $createUser = $this->instantiateValidChangeUserName();

        $this->assertSame('Bruce', $createUser->firstName());
    }

    /** @test */
    public function itReturnsTheUsersLastname(): void
    {
        $createUser = $this->instantiateValidChangeUserName();

        $this->assertSame('Wayne', $createUser->lastName());
    }

    private function instantiateValidChangeUserName(): ChangeUserName
    {
        return new ChangeUserName(
            Uuid::fromString('3d8fbf56-3a34-465b-9776-c3b69c510eef'),
            'Bruce',
            'Wayne'
        );
    }
}
