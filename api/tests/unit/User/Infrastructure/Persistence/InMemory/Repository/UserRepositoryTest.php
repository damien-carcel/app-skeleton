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

namespace Carcel\Tests\Unit\User\Infrastructure\Persistence\InMemory\Repository;

use Carcel\Tests\Fixtures\UserFixtures;
use Carcel\User\Domain\Factory\UserFactory;
use Carcel\User\Domain\Model\Write\Email;
use Carcel\User\Domain\Model\Write\FirstName;
use Carcel\User\Domain\Model\Write\LastName;
use Carcel\User\Domain\Model\Write\User;
use Carcel\User\Infrastructure\Persistence\InMemory\Repository\InMemoryUserRepository;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class UserRepositoryTest extends TestCase
{
    private array $userIDs;
    private array $users;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userIDs = ['02432f0b-c33e-4d71-8ba9-a5e3267a45d5', '08acf31d-2e62-44e9-ba18-fd160ac125ad'];
        $this->users = [
            $this->instantiateUser($this->userIDs[0]),
            $this->instantiateUser($this->userIDs[1]),
        ];
    }

    /** @test */
    public function itFindAllUsers(): void
    {
        $repository = $this->instantiateRepository();

        static::assertSame($this->users, $repository->findAll());
    }

    /** @test */
    public function itFindAUserFromItsId(): void
    {
        $repository = $this->instantiateRepository();

        static::assertSame($this->users[0], $repository->find(Uuid::fromString($this->userIDs[0])));
    }

    /** @test */
    public function itCreatesAUser(): void
    {
        $repository = $this->instantiateRepository();

        $user = $this->instantiateUser('1605a575-77e5-4427-bbdb-2ebcb8cc8033');
        $repository->create($user);

        static::assertCount(3, $repository->findAll());
        static::assertSame($user, $repository->find(Uuid::fromString('1605a575-77e5-4427-bbdb-2ebcb8cc8033')));
    }

    /** @test */
    public function itUpdatesAUser(): void
    {
        $repository = $this->instantiateRepository();

        $user = $repository->find(Uuid::fromString($this->userIDs[0]));
        $user->update(
            FirstName::fromString('New first name'),
            LastName::fromString('New last name'),
            Email::fromString('new.email@avengers.org'),
        );
        $repository->update($user);

        static::assertCount(2, $repository->findAll());
        static::assertSame($user, $repository->find(Uuid::fromString($this->userIDs[0])));
    }

    /** @test */
    public function itDeletesAUser(): void
    {
        $repository = $this->instantiateRepository();

        $repository->delete($this->users[0]);

        static::assertCount(1, $repository->findAll());
        static::assertNull($repository->find(Uuid::fromString($this->userIDs[0])));
    }

    private function instantiateRepository(): InMemoryUserRepository
    {
        return new InMemoryUserRepository($this->users);
    }

    private function instantiateUser(string $userId): User
    {
        $factory = new UserFactory();

        return $factory->create(
            $userId,
            UserFixtures::USERS_DATA[$userId]['firstName'],
            UserFixtures::USERS_DATA[$userId]['lastName'],
            UserFixtures::USERS_DATA[$userId]['email'],
            UserFixtures::USERS_DATA[$userId]['password'],
        );
    }
}
