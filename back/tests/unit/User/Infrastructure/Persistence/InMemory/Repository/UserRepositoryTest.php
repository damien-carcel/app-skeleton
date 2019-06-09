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

use Carcel\Tests\Fixtures\UserFixtures;
use Carcel\User\Domain\Model\Write\User;
use Carcel\User\Infrastructure\Persistence\InMemory\Repository\UserRepository;
use PHPUnit\Framework\TestCase;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
class UserRepositoryTest extends TestCase
{
    /** @var string[] */
    private $userIDs;

    /** @var User[] */
    private $users;

    protected function setUp(): void
    {
        $this->userIDs = ['02432f0b-c33e-4d71-8ba9-a5e3267a45d5', '08acf31d-2e62-44e9-ba18-fd160ac125ad'];
        $this->users = [
            UserFixtures::instantiateUserEntity($this->userIDs[0]),
            UserFixtures::instantiateUserEntity($this->userIDs[1]),
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

        $this->assertSame($this->users[0], $repository->find($this->userIDs[0]));
    }

    /** @test */
    public function itSavesAUser(): void
    {
        $user = UserFixtures::instantiateUserEntity('1605a575-77e5-4427-bbdb-2ebcb8cc8033');

        $repository = $this->instantiateRepository();
        $repository->save($user);

        $this->assertCount(3, $repository->findAll());
        $this->assertSame($user, $repository->find('1605a575-77e5-4427-bbdb-2ebcb8cc8033'));
    }

    /** @test */
    public function itDeletesAUser(): void
    {
        $repository = $this->instantiateRepository();

        $repository->delete($this->users[0]);

        $this->assertCount(1, $repository->findAll());
        $this->assertNull($repository->find($this->userIDs[0]));
    }

    /**
     * @return UserRepository
     */
    private function instantiateRepository(): UserRepository
    {
        return new UserRepository($this->users);
    }
}
