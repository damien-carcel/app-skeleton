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

namespace Carcel\Tests\Unit\User\Domain\Model\Write;

use Carcel\Tests\Fixtures\UserFixtures;
use Carcel\User\Domain\Model\Write\Email;
use Carcel\User\Domain\Model\Write\FirstName;
use Carcel\User\Domain\Model\Write\LastName;
use Carcel\User\Domain\Model\Write\User;
use PHPUnit\Framework\TestCase;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class UserTest extends TestCase
{
    /** @test */
    public function itIsAUser(): void
    {
        static::assertInstanceOf(User::class, $this->instantiateTonyStark());
    }

    /** @test */
    public function itHasAnIdentifier(): void
    {
        $user = $this->instantiateTonyStark();

        static::assertSame('02432f0b-c33e-4d71-8ba9-a5e3267a45d5', (string) $user->id());
    }

    /** @test */
    public function itReturnsTheUserEmail(): void
    {
        $user = $this->instantiateTonyStark();

        static::assertInstanceOf(Email::class, $user->email());
        static::assertSame('ironman@avengers.org', (string) $user->email());
    }

    /** @test */
    public function itReturnsTheFirstName(): void
    {
        $user = $this->instantiateTonyStark();

        static::assertInstanceOf(FirstName::class, $user->firstName());
        static::assertSame('Tony', (string) $user->firstName());
    }

    /** @test */
    public function itReturnsTheLastName(): void
    {
        $user = $this->instantiateTonyStark();

        static::assertInstanceOf(LastName::class, $user->lastName());
        static::assertSame('Stark', (string) $user->lastName());
    }

    /** @test */
    public function aUserCanChangeItsName(): void
    {
        $user = $this->instantiateTonyStark();

        $user->update(
            Email::fromString('new.ironman@advengers.org'),
            FirstName::fromString('Peter'),
            LastName::fromString('Parker')
        );

        static::assertSame('new.ironman@advengers.org', (string) $user->email());
        static::assertSame('Peter', (string) $user->firstName());
        static::assertSame('Parker', (string) $user->lastName());
    }

    private function instantiateTonyStark(): User
    {
        return UserFixtures::instantiateUserEntity('02432f0b-c33e-4d71-8ba9-a5e3267a45d5');
    }
}
