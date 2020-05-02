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

use Carcel\User\Domain\Model\Write\Password;
use PHPUnit\Framework\TestCase;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class PasswordTest extends TestCase
{
    /** @test */
    public function itReturnsThePassword(): void
    {
        static::assertSame(
            'Password',
            (string) Password::fromString('Password')
        );
    }

    /** @test */
    public function passwordCannotBeEmpty(): void
    {
        static::expectException(\InvalidArgumentException::class);
        static::expectExceptionMessage('The user password cannot be empty.');

        Password::fromString('');
    }

    /** @test */
    public function passwordMustNotBeTooLong(): void
    {
        $tooLongPassword = bin2hex(random_bytes(130));

        static::expectException(\InvalidArgumentException::class);
        static::expectExceptionMessage(sprintf(
            'The password should not have more than 256 characters, "%s" is 260 characters long.',
            $tooLongPassword
        ));

        Password::fromString($tooLongPassword);
    }
}
