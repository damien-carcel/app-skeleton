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

namespace Carcel\Tests\Integration\User\Infrastructure\Persistence\Doctrine\QueryFunction;

use Carcel\Tests\Fixtures\UserFixtures;
use Carcel\Tests\Integration\TestCase;
use Carcel\User\Domain\QueryFunction\GetUserPassword;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class GetUserPasswordFromDatabaseTest extends TestCase
{
    private const TONY_STARK_ID = '02432f0b-c33e-4d71-8ba9-a5e3267a45d5';

    /**
     * {@inheritdoc}
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->loadUserFixtures();
    }

    /** @test */
    public function itGetsAUserByEmail(): void
    {
        $password = ($this->getUserPassword())(
            UserFixtures::USERS_DATA[self::TONY_STARK_ID]['email']
        );

        self::assertSame(UserFixtures::getPassword(self::TONY_STARK_ID), $password);
    }

    /** @test */
    public function itDoesntGetByEmailAUserThatDoesNotExist(): void
    {
        $password = ($this->getUserPassword())('fake.email@whatever.com');

        self::assertNull($password);
    }

    private function getUserPassword(): GetUserPassword
    {
        return $this->container()->get(GetUserPassword::class);
    }
}
