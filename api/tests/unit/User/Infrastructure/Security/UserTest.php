<?php

declare(strict_types=1);

/*
 * This file is part of app-skeleton.
 *
 * Copyright (c) 2020 Damien Carcel <damien.carcel@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Carcel\Tests\Unit\User\Infrastructure\Security;

use Carcel\Tests\Fixtures\UserFixtures;
use Carcel\User\Infrastructure\Security\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    private const TONY_STARK_ID = '02432f0b-c33e-4d71-8ba9-a5e3267a45d5';

    /** @test */
    public function itReturnsTheUserName(): void
    {
        $user = $this->instantiateTonyStarkAsSecurityUser();

        self::assertSame(UserFixtures::USERS_DATA[static::TONY_STARK_ID]['email'], $user->getUsername());
    }

    /** @test */
    public function itReturnsThePassword(): void
    {
        $user = $this->instantiateTonyStarkAsSecurityUser();

        self::assertSame('', $user->getPassword());
    }

    /** @test */
    public function itReturnsTheUserRoles(): void
    {
        $user = $this->instantiateTonyStarkAsSecurityUser();

        self::assertSame(['ROLE_USER'], $user->getRoles());
    }

    private function instantiateTonyStarkAsSecurityUser(): User
    {
        return new User(
            UserFixtures::USERS_DATA[static::TONY_STARK_ID]['email'],
            '',
            [],
        );
    }
}
