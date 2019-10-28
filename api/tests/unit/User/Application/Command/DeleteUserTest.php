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

use Carcel\User\Application\Command\DeleteUser;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class DeleteUserTest extends TestCase
{
    /** @test */
    public function itIsACreateUserCommand(): void
    {
        $deleteUser = $this->instantiateValidDeleteUser();

        static::assertInstanceOf(DeleteUser::class, $deleteUser);
    }

    /** @test */
    public function itReturnsTheUsersEmail(): void
    {
        $deleteUser = $this->instantiateValidDeleteUser();

        static::assertEquals(Uuid::fromString('df25ad55-126d-4160-89ff-a974725cb183'), $deleteUser->identifier());
    }

    private function instantiateValidDeleteUser(): DeleteUser
    {
        return new DeleteUser(Uuid::fromString('df25ad55-126d-4160-89ff-a974725cb183'));
    }
}
