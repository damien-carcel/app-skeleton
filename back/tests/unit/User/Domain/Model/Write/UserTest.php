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

namespace Carcel\Tests\Unit\User\Domain\Model\Write;

use Carcel\User\Domain\Model\Write\User;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
class UserTest extends TestCase
{
    /** @var UuidInterface */
    private $uuid;

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    protected function setup(): void
    {
        $this->uuid = Uuid::uuid4();
    }

    /** @test */
    public function itCanCreateAUser(): void
    {
        $this->assertInstanceOf(User::class, $this->instantiateValidUser());
    }

    /** @test */
    public function itHasAnUUID(): void
    {
        $user = $this->instantiateValidUser();

        $this->assertSame($this->uuid, $user->id());
    }

    /** @test */
    public function itReturnsTheUserName(): void
    {
        $user = $this->instantiateValidUser();

        $this->assertSame('ironman', $user->getUsername());
    }

    /** @test */
    public function itReturnsTheFirstName(): void
    {
        $user = $this->instantiateValidUser();

        $this->assertSame('Tony', $user->getFirstName());
    }

    /** @test */
    public function itReturnsTheLastName(): void
    {
        $user = $this->instantiateValidUser();

        $this->assertSame('Stark', $user->getLastName());
    }

    /** @test */
    public function itReturnTheUserRoles(): void
    {
        $user = $this->instantiateValidUser();

        $this->assertSame([], $user->getRoles());
    }

    /** @test */
    public function itReturnTheUserPassword(): void
    {
        $user = $this->instantiateValidUser();

        $this->assertSame('password', $user->getPassword());
    }

    /** @test */
    public function itReturnTheSalt(): void
    {
        $user = $this->instantiateValidUser();

        $this->assertSame('salt', $user->getSalt());
    }

    /** @test */
    public function itCanEraseTheCredentials(): void
    {
        $user = $this->instantiateValidUser();

        $this->expectException(\LogicException::class);

        $user->eraseCredentials();
    }

    /** @test */
    public function aUserCanChangeItsName(): void
    {
        $user = $this->instantiateValidUser();

        $user->changeName(['firstName' => 'Peter', 'lastName' => 'Parker']);

        $this->assertSame('Peter', $user->getFirstName());
        $this->assertSame('Parker', $user->getLastName());
    }

    /**
     * @return User
     */
    private function instantiateValidUser(): User
    {
        return new User($this->uuid, 'ironman', 'Tony', 'Stark', 'password', 'salt', []);
    }
}
