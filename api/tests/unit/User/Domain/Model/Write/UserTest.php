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
    public function itHasAnIdentifier(): void
    {
        $user = $this->instantiateTonyStark();

        $this->assertSame('02432f0b-c33e-4d71-8ba9-a5e3267a45d5', (string) $user->id());
    }

    /** @test */
    public function itReturnsTheUsername(): void
    {
        $user = $this->instantiateTonyStark();

        $this->assertSame('ironman', $user->username());
    }

    /** @test */
    public function itReturnsTheFirstName(): void
    {
        $user = $this->instantiateTonyStark();

        $this->assertSame('Tony', $user->firstName());
    }

    /** @test */
    public function itReturnsTheLastName(): void
    {
        $user = $this->instantiateTonyStark();

        $this->assertSame('Stark', $user->lastName());
    }

    /** @test */
    public function aUserCanChangeItsName(): void
    {
        $user = $this->instantiateTonyStark();

        $user->changeName('Peter', 'Parker');

        $this->assertSame('Peter', $user->firstName());
        $this->assertSame('Parker', $user->lastName());
    }

    private function instantiateTonyStark(): User
    {
        return UserFixtures::instantiateUserEntity('02432f0b-c33e-4d71-8ba9-a5e3267a45d5');
    }
}
