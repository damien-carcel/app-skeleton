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

use Carcel\User\Domain\Model\Write\LastName;
use PHPUnit\Framework\TestCase;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class LastNameTest extends TestCase
{
    /** @test */
    public function itReturnsTheLastName(): void
    {
        static::assertSame(
            'Tony',
            (string) LastName::fromString('Tony')
        );
    }

    /** @test */
    public function lastNameCannotBeEmpty(): void
    {
        static::expectException(\InvalidArgumentException::class);
        static::expectExceptionMessage('The user last name cannot be empty.');

        LastName::fromString('');
    }

    /** @test */
    public function lastNameMustNotBeTooLong(): void
    {
        $tooLongLastName = bin2hex(random_bytes(130));

        static::expectException(\InvalidArgumentException::class);
        static::expectExceptionMessage(sprintf(
            'The last name should not have more than 256 characters, "%s" is 260 characters long.',
            $tooLongLastName
        ));

        LastName::fromString($tooLongLastName);
    }
}
