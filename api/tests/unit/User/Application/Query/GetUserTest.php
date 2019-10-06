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

namespace Carcel\Tests\Unit\User\Application\Query;

use Carcel\User\Application\Query\GetUser;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class GetUserTest extends TestCase
{
    private const USER_IDENTIFIER = '02432f0b-c33e-4d71-8ba9-a5e3267a45d5';

    /** @test */
    public function itIsAGetUserQuery(): void
    {
        $getUser = $this->instantiateValidGetUser();

        $this->assertInstanceOf(GetUser::class, $getUser);
    }

    /** @test */
    public function itReturnsTheUserIdentifier(): void
    {
        $getUser = $this->instantiateValidGetUser();
        $this->assertSame(static::USER_IDENTIFIER, $getUser->userIdentifier()->toString());
    }

    private function instantiateValidGetUser(): GetUser
    {
        return new GetUser(Uuid::fromString(static::USER_IDENTIFIER));
    }
}
