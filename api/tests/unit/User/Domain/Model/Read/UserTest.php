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

namespace Carcel\Tests\Unit\User\Domain\Model\Read;

use Carcel\Tests\Fixtures\UserFixtures;
use Carcel\User\Domain\Model\Read\User;
use PHPUnit\Framework\TestCase;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class UserTest extends TestCase
{
    private array $userData;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->userData = UserFixtures::getNormalizedUsers()[0];
    }

    /** @test */
    public function aUserReadModelCanNormalizeItself(): void
    {
        $user = $this->instantiateValidUserReadModel();

        static::assertSame($this->userData, $user->normalize());
    }

    private function instantiateValidUserReadModel(): User
    {
        list($id, $firstName, $lastName, $email) = array_values($this->userData);

        return new User($id, $firstName, $lastName, $email);
    }
}
