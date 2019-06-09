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

namespace Carcel\Tests\Unit\User\Domain\Factory;

use Carcel\Tests\Fixtures\UserFixtures;
use Carcel\User\Domain\Factory\UserFactory;
use Carcel\User\Domain\Model\Write\User;
use PHPUnit\Framework\TestCase;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class UserFactoryTest extends TestCase
{
    /** @test */
    public function itIsAUserFactory(): void
    {
        $this->assertInstanceOf(UserFactory::class, $this->instantiateUserFactory());
    }

    /** @test */
    public function itCreatesANewUser(): void
    {
        $user = $this->instantiateUserFactory()->create(
            UserFixtures::USERS_DATA['02432f0b-c33e-4d71-8ba9-a5e3267a45d5']
        );

        $this->assertInstanceOf(User::class, $user);
    }

    /** @test */
    public function itCreatesANewUserIfUuidIsEmpty(): void
    {
        $user = $this->instantiateUserFactory()->create(array_merge(
            ['id' => ''],
            UserFixtures::USERS_DATA['02432f0b-c33e-4d71-8ba9-a5e3267a45d5']
        ));

        $this->assertInstanceOf(User::class, $user);
    }

    /** @test */
    public function itCreatesANewUserWithAProvidedUuid(): void
    {
        $user = $this->instantiateUserFactory()->create(array_merge(
            ['id' => '02432f0b-c33e-4d71-8ba9-a5e3267a45d5'],
            UserFixtures::USERS_DATA['02432f0b-c33e-4d71-8ba9-a5e3267a45d5']
        ));

        $this->assertInstanceOf(User::class, $user);
        $this->assertSame('02432f0b-c33e-4d71-8ba9-a5e3267a45d5', (string) $user->id());
    }

    /** @test */
    public function itThrowsAnExceptionIfProvidedUuidIsNotValid(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Provided user ID "not_an_uuid" is not a valid UUID.');

        $this->instantiateUserFactory()->create(array_merge(
            ['id' => 'not_an_uuid'],
            UserFixtures::USERS_DATA['02432f0b-c33e-4d71-8ba9-a5e3267a45d5']
        ));
    }

    private function instantiateUserFactory(): UserFactory
    {
        return new UserFactory();
    }
}
