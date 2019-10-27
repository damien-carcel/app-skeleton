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

use Carcel\User\Application\Command\CreateUser;
use PHPUnit\Framework\TestCase;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class CreateUserTest extends TestCase
{
    /** @test */
    public function itIsACreateUserCommand(): void
    {
        $createUser = $this->instantiateValidCreateUser();

        static::assertInstanceOf(CreateUser::class, $createUser);
    }

    /** @test */
    public function itReturnsTheUsersEmail(): void
    {
        $createUser = $this->instantiateValidCreateUser();

        static::assertSame('ironman', $createUser->email());
    }

    /** @test */
    public function itReturnsTheUsersFirstname(): void
    {
        $createUser = $this->instantiateValidCreateUser();

        static::assertSame('Tony', $createUser->firstName());
    }

    /** @test */
    public function itReturnsTheUsersLastname(): void
    {
        $createUser = $this->instantiateValidCreateUser();

        static::assertSame('Stark', $createUser->lastName());
    }

    private function instantiateValidCreateUser(): CreateUser
    {
        return new CreateUser('ironman', 'Tony', 'Stark');
    }
}
