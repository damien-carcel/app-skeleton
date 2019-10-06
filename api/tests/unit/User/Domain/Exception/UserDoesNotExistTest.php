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

namespace Carcel\Tests\Unit\User\Domain\Exception;

use Carcel\Tests\Fixtures\UserFixtures;
use Carcel\User\Domain\Exception\UserDoesNotExist;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class UserDoesNotExistTest extends TestCase
{
    /** @test */
    public function itIsAUserDoesNotExistException(): void
    {
        $exception = $this->instantiateUserDoesNotExist();
        $this->assertInstanceOf(UserDoesNotExist::class, $exception);
    }

    /** @test */
    public function itReturnsAMessage(): void
    {
        $exception = $this->instantiateUserDoesNotExist();
        $this->assertSame(
            sprintf('There is no user with identifier "%s"', UserFixtures::ID_OF_NON_EXISTENT_USER),
            $exception->getMessage()
        );
    }

    private function instantiateUserDoesNotExist(): UserDoesNotExist
    {
        return UserDoesNotExist::fromUuid(Uuid::fromString(UserFixtures::ID_OF_NON_EXISTENT_USER));
    }
}
