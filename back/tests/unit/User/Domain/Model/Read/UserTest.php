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

namespace Carcel\Tests\Unit\User\Domain\Model\Read;

use Carcel\Tests\Fixtures\UserFixtures;
use Carcel\User\Domain\Model\Read\User;
use PHPUnit\Framework\TestCase;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
class UserTest extends TestCase
{
    /** @var array */
    private $userData;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->userData = UserFixtures::getNormalizedUsers()[0];
    }

    /** @test */
    public function itCanCreateAUserReadModel(): void
    {
        $this->assertInstanceOf(User::class, $this->instantiateValidUserReadModel());
    }

    /** @test */
    public function aUserReadModelCanNormalizeItself(): void
    {
        $user = $this->instantiateValidUserReadModel();

        $this->assertSame($this->userData, $user->normalize());
    }

    /**
     * @return User
     */
    private function instantiateValidUserReadModel(): User
    {
        list($id, $username, $firstName, $lastName) = array_values($this->userData);

        return new User($id, $username, $firstName, $lastName);
    }
}
