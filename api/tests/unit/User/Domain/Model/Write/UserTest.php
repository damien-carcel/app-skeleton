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

namespace Carcel\Tests\Unit\User\Domain\Model\Write;

use Carcel\Tests\Fixtures\UserFixtures;
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
        $this->assertInstanceOf(User::class, $this->instantiateTonyStark());
    }

    /** @test */
    public function itHasAnUUID(): void
    {
        $user = $this->instantiateTonyStark();

        $this->assertSame('02432f0b-c33e-4d71-8ba9-a5e3267a45d5', (string) $user->id());
    }

    /** @test */
    public function itReturnsTheUserName(): void
    {
        $user = $this->instantiateTonyStark();

        $this->assertSame('ironman', $user->getUsername());
    }

    /** @test */
    public function itReturnsTheFirstName(): void
    {
        $user = $this->instantiateTonyStark();

        $this->assertSame('Tony', $user->getFirstName());
    }

    /** @test */
    public function itReturnsTheLastName(): void
    {
        $user = $this->instantiateTonyStark();

        $this->assertSame('Stark', $user->getLastName());
    }

    /** @test */
    public function itReturnTheUserRoles(): void
    {
        $user = $this->instantiateTonyStark();

        $this->assertSame([], $user->getRoles());
    }

    /** @test */
    public function itReturnTheUserPassword(): void
    {
        $user = $this->instantiateTonyStark();

        $this->assertSame('password', $user->getPassword());
    }

    /** @test */
    public function itReturnTheSalt(): void
    {
        $user = $this->instantiateTonyStark();

        $this->assertSame('salt', $user->getSalt());
    }

    /** @test */
    public function itCanEraseTheCredentials(): void
    {
        $user = $this->instantiateTonyStark();

        $this->expectException(\LogicException::class);

        $user->eraseCredentials();
    }

    /** @test */
    public function aUserCanChangeItsName(): void
    {
        $user = $this->instantiateTonyStark();

        $user->changeName(['firstName' => 'Peter', 'lastName' => 'Parker']);

        $this->assertSame('Peter', $user->getFirstName());
        $this->assertSame('Parker', $user->getLastName());
    }

    private function instantiateTonyStark(): User
    {
        return UserFixtures::instantiateUserEntity('02432f0b-c33e-4d71-8ba9-a5e3267a45d5');
    }
}
