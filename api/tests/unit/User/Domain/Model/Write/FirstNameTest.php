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

use Carcel\User\Domain\Model\Write\FirstName;
use PHPUnit\Framework\TestCase;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class FirstNameTest extends TestCase
{
    /** @test */
    public function itReturnsTheFirstName(): void
    {
        static::assertSame(
            'Tony',
            (string) FirstName::fromString('Tony')
        );
    }

    /** @test */
    public function firstNameCannotBeEmpty(): void
    {
        static::expectException(\InvalidArgumentException::class);
        static::expectExceptionMessage('The user first name cannot be empty.');

        FirstName::fromString('');
    }

    /** @test */
    public function firstNameMustNotBeTooLong(): void
    {
        $tooLongFirstName = bin2hex(random_bytes(130));

        static::expectException(\InvalidArgumentException::class);
        static::expectExceptionMessage(sprintf(
            'The first name should not be more than 256 characters, "%s" is 260 characters long.',
            $tooLongFirstName
        ));

        FirstName::fromString($tooLongFirstName);
    }
}
