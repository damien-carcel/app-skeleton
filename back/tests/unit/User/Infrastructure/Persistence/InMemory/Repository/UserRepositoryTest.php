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

namespace Carcel\Tests\Unit\User\Infrastructure\Persistence\InMemory\Repository;

use Carcel\User\Domain\Model\Write\User;
use Carcel\User\Infrastructure\Persistence\InMemory\Repository\UserRepository;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
class UserRepositoryTest extends TestCase
{
    /** @var UuidInterface[] */
    private $userIDs;

    /** @var User[] */
    private $users;

    protected function setUp(): void
    {
        $this->userIDs = [Uuid::uuid4(), Uuid::uuid4()];
        $this->users = [
            new User($this->userIDs[0], 'foobar', 'foo', 'bar', 'pass', 'salt', []),
            new User($this->userIDs[1], 'barbaz', 'bar', 'baz', 'pass', 'salt', []),
        ];
    }

    /** @test */
    public function itCanBeInstantiated(): void
    {
        $this->assertInstanceOf(UserRepository::class, $this->instantiateRepository());
    }

    /** @test */
    public function itFindAllUsers(): void
    {
        $repository = $this->instantiateRepository();

        $this->assertSame($this->users, $repository->findAll());
    }

    /** @test */
    public function itFindAUserFromItsId(): void
    {
        $repository = $this->instantiateRepository();

        $this->assertSame($this->users[0], $repository->find((string) $this->userIDs[0]));
    }

    /** @test */
    public function itSavesAUser(): void
    {
        $userId = Uuid::uuid4();
        $user = new User($userId, 'foobarbaz', 'foobar', 'barbaz', 'pass', 'salt', []);

        $repository = $this->instantiateRepository();
        $repository->save($user);

        $this->assertCount(3, $repository->findAll());
        $this->assertSame($user, $repository->find((string) $userId));
    }

    /** @test */
    public function itDeletesAUser(): void
    {
        $repository = $this->instantiateRepository();

        $repository->delete($this->users[0]);

        $this->assertCount(1, $repository->findAll());
        $this->assertNull($repository->find((string) $this->userIDs[0]));
    }

    /**
     * @return UserRepository
     */
    private function instantiateRepository(): UserRepository
    {
        return new UserRepository($this->users);
    }
}
