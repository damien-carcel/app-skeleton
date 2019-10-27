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

namespace Carcel\Tests\Integration\User\Infrastructure\Persistence\Doctrine\QueryFunction;

use Carcel\Tests\Fixtures\UserFixtures;
use Carcel\Tests\Integration\TestCase;
use Carcel\User\Domain\Model\Read\User;
use Carcel\User\Domain\QueryFunction\GetUser;
use Ramsey\Uuid\Uuid;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class GetUserFromDatabaseTest extends TestCase
{
    /**
     * {@inheritdoc}
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->loadUserFixtures();
    }

    /** @test */
    public function itGetsAUser(): void
    {
        $user = ($this->getUserFromDatabase())(Uuid::fromString('02432f0b-c33e-4d71-8ba9-a5e3267a45d5'));

        static::assertUserShouldBeRetrieved($user, '02432f0b-c33e-4d71-8ba9-a5e3267a45d5');
    }

    /** @test */
    public function itDoesntGetAUserThatDoesNotExist(): void
    {
        $user = ($this->getUserFromDatabase())(Uuid::fromString(UserFixtures::ID_OF_NON_EXISTENT_USER));

        static::assertNull($user);
    }

    private function getUserFromDatabase(): GetUser
    {
        return $this->container()->get(GetUser::class);
    }

    private function assertUserShouldBeRetrieved(User $user, string $usersId): void
    {
        static::assertSame($user->normalize(), UserFixtures::getNormalizedUser($usersId));
    }
}
