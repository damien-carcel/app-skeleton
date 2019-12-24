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

namespace Carcel\Tests\Integration\User\Infrastructure\Persistence\Doctrine\Repository;

use Carcel\Tests\Fixtures\UserFixtures;
use Carcel\Tests\Integration\TestCase;
use Carcel\User\Domain\Repository\UserRepositoryInterface;
use Ramsey\Uuid\Uuid;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class UserRepositoryTest extends TestCase
{
    /** @var string[] */
    private $userIDs;

    /**
     * {@inheritdoc}
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->userIDs = ['02432f0b-c33e-4d71-8ba9-a5e3267a45d5', '08acf31d-2e62-44e9-ba18-fd160ac125ad'];

        $this->loadUserFixtures($this->userIDs);
    }

    /** @test */
    public function itFindAllUsers(): void
    {
        $users = $this->repository()->findAll();

        static::assertCount(2, $users);
        static::assertEquals([
            UserFixtures::instantiateUserEntity($this->userIDs[0]),
            UserFixtures::instantiateUserEntity($this->userIDs[1]),
        ], $users);
    }

    /** @test */
    public function itFindAUserFromItsId(): void
    {
        static::assertEquals(
            UserFixtures::instantiateUserEntity($this->userIDs[0]),
            $this->repository()->find(Uuid::fromString($this->userIDs[0]))
        );
    }

    /** @test */
    public function itSavesAUser(): void
    {
        $user = UserFixtures::instantiateUserEntity('1605a575-77e5-4427-bbdb-2ebcb8cc8033');

        $this->repository()->save($user);

        static::assertCount(3, $this->repository()->findAll());
        static::assertSame($user, $this->repository()->find(Uuid::fromString('1605a575-77e5-4427-bbdb-2ebcb8cc8033')));
    }

    /** @test */
    public function itDeletesAUser(): void
    {
        $this->repository()->delete($this->repository()->find(Uuid::fromString($this->userIDs[0])));

        static::assertCount(1, $this->repository()->findAll());
        static::assertNull($this->repository()->find(Uuid::fromString($this->userIDs[0])));
    }

    private function repository(): UserRepositoryInterface
    {
        return $this->container()->get(UserRepositoryInterface::class);
    }
}
