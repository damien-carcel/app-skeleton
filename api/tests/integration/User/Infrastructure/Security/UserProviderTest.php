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

namespace Carcel\Tests\Integration\User\Infrastructure\Security;

use Carcel\Tests\Fixtures\UserFixtures;
use Carcel\Tests\Integration\TestCase;
use Carcel\User\Domain\QueryFunction\GetUserPassword;
use Carcel\User\Infrastructure\Security\User;
use Carcel\User\Infrastructure\Security\UserProvider;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\User as SymfonyTestUser;
use Symfony\Component\Security\Core\User\UserInterface;

class UserProviderTest extends TestCase
{
    private const TONY_STARK_ID = '02432f0b-c33e-4d71-8ba9-a5e3267a45d5';

    /**
     * {@inheritdoc}
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->loadUserFixtures([static::TONY_STARK_ID]);
    }

    /** @test */
    public function itLoadsAUserByItsUsername(): void
    {
        $user = $this->userProvider()->loadUserByUsername(UserFixtures::USERS_DATA[static::TONY_STARK_ID]['email']);

        static::assertInstanceOf(User::class, $user);
        static::assertSame(
            UserFixtures::USERS_DATA[static::TONY_STARK_ID]['email'],
            $user->getUsername()
        );
    }

    /** @test */
    public function itCannotLoadAUserThatDoesNotExists(): void
    {
        static::expectException(UsernameNotFoundException::class);

        $this->userProvider()->loadUserByUsername('fake.email@whatever.com');
    }

    /**
     * @test
     *
     * TODO: Improve once/if roles are stored in the user database.
     */
    public function itRefreshesAUser(): void
    {
        $user = new User(UserFixtures::USERS_DATA[static::TONY_STARK_ID]['email'], 'fake-password', ['FAKE_ROLE']);

        $refreshedUser = $this->userProvider()->refreshUser($user);

        static::assertSame(
            UserFixtures::USERS_DATA[static::TONY_STARK_ID]['email'],
            $refreshedUser->getUsername()
        );
        static::assertSame('password', $refreshedUser->getPassword());
        static::assertSame(['ROLE_USER'], $refreshedUser->getRoles());
    }

    /** @test */
    public function itCannotRefreshAnInvalidUserClass(): void
    {
        static::expectException(UnsupportedUserException::class);

        $this->userProvider()->refreshUser(new SymfonyTestUser('fake.email@whatever.com', '', []));
    }

    /** @test */
    public function itCannotRefreshAUserThatDoesNotExists(): void
    {
        static::expectException(UsernameNotFoundException::class);

        $this->userProvider()->refreshUser(new User('fake.email@whatever.com', '', []));
    }

    /** @test */
    public function itUpgradesTheUserPassword(): void
    {
        $user = $this->userProvider()->loadUserByUsername(UserFixtures::USERS_DATA[static::TONY_STARK_ID]['email']);

        $this->userProvider()->upgradePassword($user, 'this_is_an_encoded_password');

        $this->assertPasswordWasUpgraded($user, 'this_is_an_encoded_password');
    }

    private function assertPasswordWasUpgraded(UserInterface $user, string $expectedPassword): void
    {
        $passwordStoredInDatabase = ($this->container()->get(GetUserPassword::class))($user->getUsername());

        static::assertSame($expectedPassword, $passwordStoredInDatabase);
    }

    private function userProvider(): UserProvider
    {
        return $this->container()->get(UserProvider::class);
    }
}
