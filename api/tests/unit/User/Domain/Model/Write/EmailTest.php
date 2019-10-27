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

namespace Carcel\Tests\Unit\User\Domain\Model\Write;

use Carcel\User\Domain\Exception\EmailCannotBeEmpty;
use Carcel\User\Domain\Model\Write\Email;
use PHPUnit\Framework\TestCase;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class EmailTest extends TestCase
{
    /** @test */
    public function itInstantiateAEmail(): void
    {
        static::assertInstanceOf(Email::class, Email::fromString('ironman@avengers.org'));
    }

    /** @test */
    public function itReturnsTheEmail(): void
    {
        static::assertSame(
            'ironman@avengers.org',
            (string) Email::fromString('ironman@avengers.org')
        );
    }

    /** @test */
    public function emailCannotBeEmpty(): void
    {
        static::expectException(\InvalidArgumentException::class);
        static::expectExceptionMessage('User email cannot be empty.');

        Email::fromString('');
    }

    /** @test */
    public function emailMustBeValid(): void
    {
        static::expectException(\InvalidArgumentException::class);
        static::expectExceptionMessage('The use email "foobar" is not a valid email address.');

        Email::fromString('foobar');
    }
}
