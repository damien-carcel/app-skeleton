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

namespace Carcel\Tests\Unit\User\Domain\Factory;

use Carcel\Tests\Fixtures\UserFixtures;
use Carcel\User\Domain\Factory\UserFactory;
use PHPUnit\Framework\TestCase;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class UserFactoryTest extends TestCase
{
    /** @test */
    public function itInstantiateAUser(): void
    {
        $userId = '02432f0b-c33e-4d71-8ba9-a5e3267a45d5';

        $factory = $this->instantiateUserFactory();
        $user = $factory->create(
            $userId,
            UserFixtures::USERS_DATA[$userId]['firstName'],
            UserFixtures::USERS_DATA[$userId]['lastName'],
            UserFixtures::USERS_DATA[$userId]['email'],
            UserFixtures::USERS_DATA[$userId]['password'],
        );

        self::assertSame('Tony', (string) $user->firstName());
        self::assertSame('Stark', (string) $user->lastName());
        self::assertSame('ironman@avengers.org', (string) $user->email());
        self::assertSame('password', (string) $user->password());
    }

    private function instantiateUserFactory(): UserFactory
    {
        return new UserFactory();
    }
}
